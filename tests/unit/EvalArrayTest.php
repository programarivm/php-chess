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
        $expectedNormd = [ -1.0, -0.33333, 0.01178, 0.09064, 0.09064, 0.09064, 0.45319, 1.0 ];
        $expectedSteinitz = 4;
        $expectedMean = 0.05045;
        $expectedMedian = 0.09064;
        $expectedMode = 0.09064;
        $expectedVar = 0.29011;
        $expectedSd = 0.53862;

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
        $expectedSd = 0.76801;

        $board = FenToBoardFactory::create('rnbqkb1r/3ppppp/p4n2/1PpP4/8/8/PP2PPPP/RNBQKBNR w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A59_Ba6()
    {
        $expectedSd = 0.76799;

        $board = FenToBoardFactory::create('rn1qkb1r/p2ppppp/b4n2/1PpP4/8/8/PP2PPPP/RNBQKBNR w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A74_g6()
    {
        $expectedSd = 0.67077;

        $board = FenToBoardFactory::create('rnbqkb1r/pp3p1p/3p1np1/2pP4/4P3/2N5/PP3PPP/R1BQKBNR w KQkq -');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }

    /**
     * @test
     */
    public function A74_a6()
    {
        $expectedSd = 0.67075;

        $board = FenToBoardFactory::create('rnbqkb1r/1p3ppp/3p1n2/p1pP4/4P3/2N5/PP3PPP/R1BQKBNR w KQkq a6');

        $sd = EvalArray::sd(self::$f, $board);

        $this->assertSame($expectedSd, $sd);
    }
}
