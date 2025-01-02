<?php

namespace Chess\Tests\Unit;

use Chess\EvalArray;
use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;

class EvalArrayTest extends AbstractUnitTestCase
{
    static private CompleteFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new CompleteFunction();
    }

    /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedNormd = [ -1.0, -0.33, 0.01, 0.08, 0.08, 0.08, 0.08, 0.42, 1.0 ];
        $expectedSteinitz = 5;
        $expectedMean = 0.0467;
        $expectedMedian = 0.08;
        $expectedMode = 0.08;
        $expectedVar = 0.2546;
        $expectedSd = 0.5046;

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
        $expectedNormd = [ -1.0, -0.31, -0.31, 0.1, 0.1, 0.1, 0.58, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.0325;
        $expectedMedian = 0.1;
        $expectedMode = 0.1;
        $expectedVar = 0.3188;
        $expectedSd = 0.5646;

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
    public function kaufman_08()
    {
        $expectedNormd = [ -1.0, -0.4, -0.2, 0.09, 0.09, 0.09, 0.09, 0.11, 0.19, 0.3, 0.3, 0.35, 1.0 ];
        $expectedSteinitz = 7;
        $expectedMean = 0.0777;
        $expectedMedian = 0.09;
        $expectedMode = 0.09;
        $expectedVar = 0.1927;
        $expectedSd = 0.439;

        $board = FenToBoardFactory::create('5r1k/6pp/1n2Q3/4p3/8/7P/PP4PK/R1B1q3 b - -');

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
        $expectedUnfilteredNormd = [ 0.5, -0.3, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5, -1.0, -1.0 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -1.0, -1.0, -0.3, 0.5, 0.5, 0.5, 0.5, 1.0, 1.0 ];
        $expectedSteinitz = 0;
        $expectedMean = -0.1083;
        $expectedMedian = 0.1;
        $expectedMode = -1.0;
        $expectedVar = 0.6624;
        $expectedSd = 0.8139;

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
        $expectedUnfilteredNormd = [ 0.5, -0.3, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5, 0.67, -1.0 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -1.0, -0.3, 0.5, 0.5, 0.5, 0.5, 0.67, 1.0, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.0308;
        $expectedMedian = 0.5;
        $expectedMode = 0.5;
        $expectedVar = 0.6273;
        $expectedSd = 0.792;

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
}
