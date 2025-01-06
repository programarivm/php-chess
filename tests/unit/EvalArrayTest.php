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
        $expectedNormd = [ -1.0, -0.33333, 0.01153, 0.08871, 0.08871, 0.08871, 0.44354, 1.0 ];
        $expectedSteinitz = 4;
        $expectedMean = 0.04848;
        $expectedMedian = 0.08871;
        $expectedMode = 0.08871;
        $expectedVar = 0.2891;
        $expectedSd = 0.53768;

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
    public function A59_a6()
    {
        $expectedSd = 0.76742;

        $board = FenToBoardFactory::create('rnbqkb1r/3ppppp/p4n2/1PpP4/8/8/PP2PPPP/RNBQKBNR w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A59_Ba6()
    {
        $expectedSd = 0.7674;

        $board = FenToBoardFactory::create('rn1qkb1r/p2ppppp/b4n2/1PpP4/8/8/PP2PPPP/RNBQKBNR w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A74_g6()
    {
        $expectedSd = 0.66759;

        $board = FenToBoardFactory::create('rnbqkb1r/pp3p1p/3p1np1/2pP4/4P3/2N5/PP3PPP/R1BQKBNR w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A74_a6()
    {
        $expectedSd = 0.66766;

        $board = FenToBoardFactory::create('rnbqkb1r/1p3ppp/3p1n2/p1pP4/4P3/2N5/PP3PPP/R1BQKBNR w KQkq a6');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function resume_a6()
    {
        $expectedNormd = [ 0, 1.0, 0, 0.30592, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.06118 ];
        $expectedSd = 0.39763;

        $board = FenToBoardFactory::create('rnbqkb1r/1p2pppp/p2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);
        $normd = EvalArray::normalization(self::$f, $board);

        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function resume_Na6()
    {
        $expectedNormd = [ 0, 1.0, 0, 0.3044, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.06088 ];
        $expectedSd = 0.39793;

        $board = FenToBoardFactory::create('r1bqkb1r/pp2pppp/n2p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);
        $normd = EvalArray::normalization(self::$f, $board);

        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSd, $sd);
    }
}
