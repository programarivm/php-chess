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
        $expectedNormd = [ -1.0, -0.3333, 0.0112, 0.0865, 0.0865, 0.0865, 0.4325, 1.0 ];
        $expectedSteinitz = 4;
        $expectedMean = 0.0462;
        $expectedMedian = 0.0865;
        $expectedMode = 0.0865;
        $expectedVar = 0.288;
        $expectedSd = 0.5367;

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
}
