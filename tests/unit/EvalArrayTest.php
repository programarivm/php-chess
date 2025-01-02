<?php

namespace Chess\Tests\Unit;

use Chess\EvalArray;
use Chess\FenToBoardFactory;
use Chess\Function\FastFunction;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;

class EvalArrayTest extends AbstractUnitTestCase
{
    static private FastFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new FastFunction();
    }

    /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedNormd = [ -1.0, -0.33, 0.01, 0.08, 0.08, 0.08, 0.42, 1.0 ];
        $expectedSteinitz = 4;
        $expectedMean = 0.0425;
        $expectedMedian = 0.08;
        $expectedMode = 0.08;
        $expectedVar = 0.2863;
        $expectedSd = 0.5351;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $normd = array_filter(EvalArray::normalization(self::$f, $board));
        sort($normd);

        $steinitz = EvalArray::steinitz(self::$f, $board);
        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);
        $var = EvalArray::var(self::$f, $board);
        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function kaufman_07()
    {
        $expectedNormd = [ -1.0, -1.0, 0.1, 0.1, 0.1, 0.58, 1.0 ];
        $expectedSteinitz = 3;
        $expectedMean = -0.0171;
        $expectedMedian = 0.1;
        $expectedMode = 0.1;
        $expectedVar = 0.4806;
        $expectedSd = 0.6933;

        $board = FenToBoardFactory::create('2r2rk1/1bqnbpp1/1p1ppn1p/pP6/N1P1P3/P2B1N1P/1B2QPP1/R2R2K1 b - -');

        $normd = array_filter(EvalArray::normalization(self::$f, $board));
        sort($normd);

        $steinitz = EvalArray::steinitz(self::$f, $board);
        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);
        $var = EvalArray::var(self::$f, $board);
        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function no_mode()
    {
        $expectedNormd = [ -1.0, 0.24, 1.0 ];
        $expectedSteinitz = 1;
        $expectedMean = 0.08;
        $expectedMedian = 0.24;
        $expectedMode = null;
        $expectedVar = 0.6795;
        $expectedSd = 0.8243;

        $board = FenToBoardFactory::create('rnbqkb1r/p1pp1ppp/1p2pn2/8/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -');

        $normd = array_filter(EvalArray::normalization(self::$f, $board));
        sort($normd);

        $steinitz = EvalArray::steinitz(self::$f, $board);
        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);
        $var = EvalArray::var(self::$f, $board);
        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A59_a6()
    {
        $expectedUnfilteredNormd = [ 0.5, -0.3, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -0.3, 0.5, 0.5, 0.5, 0.5, 1.0, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.07;
        $expectedMedian = 0.5;
        $expectedMode = 0.5;
        $expectedVar = 0.6041;
        $expectedSd = 0.7772;

        $board = FenToBoardFactory::create('rnbqkb1r/3ppppp/p4n2/1PpP4/8/8/PP2PPPP/RNBQKBNR w KQkq -');

        $unfilteredNormd = EvalArray::normalization(self::$f, $board);
        $normd = array_filter($unfilteredNormd);
        sort($normd);

        $steinitz = EvalArray::steinitz(self::$f, $board);
        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);
        $var = EvalArray::var(self::$f, $board);
        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedUnfilteredNormd, $unfilteredNormd);
        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A59_Ba6()
    {
        $expectedUnfilteredNormd = [ 0.5, -0.3, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -0.3, 0.5, 0.5, 0.5, 0.5, 1.0, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.07;
        $expectedMedian = 0.5;
        $expectedMode = 0.5;
        $expectedVar = 0.6041;
        $expectedSd = 0.7772;

        $board = FenToBoardFactory::create('rn1qkb1r/p2ppppp/b4n2/1PpP4/8/8/PP2PPPP/RNBQKBNR w KQkq -');

        $unfilteredNormd = EvalArray::normalization(self::$f, $board);
        $normd = array_filter($unfilteredNormd);
        sort($normd);

        $steinitz = EvalArray::steinitz(self::$f, $board);
        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);
        $var = EvalArray::var(self::$f, $board);
        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedUnfilteredNormd, $unfilteredNormd);
        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function d4_Nf6_c4_c5_d5_b5_cxb5_a6()
    {
        $expectedUnfilteredNormd = [ 0.5, -0.3, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -0.3, 0.5, 0.5, 0.5, 0.5, 1.0, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.07;
        $expectedMedian = 0.5;
        $expectedMode = 0.5;
        $expectedVar = 0.6041;
        $expectedSd = 0.7772;

        $movetext = '1.d4 Nf6 2.c4 c5 3.d5 b5 4.cxb5 a6';
        $board = (new SanPlay($movetext))->validate()->board;

        $unfilteredNormd = EvalArray::normalization(self::$f, $board);
        $normd = array_filter($unfilteredNormd);
        sort($normd);

        $steinitz = EvalArray::steinitz(self::$f, $board);
        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);
        $var = EvalArray::var(self::$f, $board);
        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedUnfilteredNormd, $unfilteredNormd);
        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function d4_Nf6_c4_c5_d5_b5_cxb5_Ba6()
    {
        $expectedUnfilteredNormd = [ 0.5, -0.3, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -0.3, 0.5, 0.5, 0.5, 0.5, 1.0, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.07;
        $expectedMedian = 0.5;
        $expectedMode = 0.5;
        $expectedVar = 0.6041;
        $expectedSd = 0.7772;

        $movetext = '1.d4 Nf6 2.c4 c5 3.d5 b5 4.cxb5 Ba6';
        $board = (new SanPlay($movetext))->validate()->board;

        $unfilteredNormd = EvalArray::normalization(self::$f, $board);
        $normd = array_filter($unfilteredNormd);
        sort($normd);

        $steinitz = EvalArray::steinitz(self::$f, $board);
        $mean = EvalArray::mean(self::$f, $board);
        $median = EvalArray::median(self::$f, $board);
        $mode = EvalArray::mode(self::$f, $board);
        $var = EvalArray::var(self::$f, $board);
        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedUnfilteredNormd, $unfilteredNormd);
        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }
}
