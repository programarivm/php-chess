<?php

namespace Chess\Tests\Unit;

use Chess\EvalArray;
use Chess\FenToBoardFactory;
use Chess\Function\FastFunction;
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
        $expectedNormd = [ -1.0, -0.33, 0.01, 0.09, 0.09, 0.09, 0.43, 1.0 ];
        $expectedSteinitz = 4;
        $expectedMean = 0.0475;
        $expectedMedian = 0.09;
        $expectedMode = 0.09;
        $expectedVar = 0.2875;
        $expectedSd = 0.5362;

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
        $expectedUnfilteredNormd = [ 0.5, -0.28, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -0.28, 0.5, 0.5, 0.5, 0.5, 1.0, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.072;
        $expectedMedian = 0.5;
        $expectedMode = 0.5;
        $expectedVar = 0.6027;
        $expectedSd = 0.7763;

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
        $expectedUnfilteredNormd = [ 0.5, -0.29, 0, 0.5, -1.0, 0, -1.0, 0, 0, 0, 1.0, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 1.0, 0, 0, 0, -1.0, 0, 0, 0, 0, 0.5 ];
        $expectedNormd = [ -1.0, -1.0, -1.0, -0.29, 0.5, 0.5, 0.5, 0.5, 1.0, 1.0 ];
        $expectedSteinitz = 2;
        $expectedMean = 0.071;
        $expectedMedian = 0.5;
        $expectedMode = 0.5;
        $expectedVar = 0.6034;
        $expectedSd = 0.7768;

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
