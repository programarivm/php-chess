<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\SanExtractor;
use Chess\Eval\FastFunction;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class SanExtractorTest extends AbstractUnitTestCase
{
    static private FastFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new FastFunction();
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function e4_d5_exd5_Qxd5()
    {
        $expectedSteinitz = [ 0, 2, 0, 0, -5 ];
        $expectedMean = [ 0.0, 0.1875, -0.125, -0.2813, -0.0664 ];
        $expectedSd = [ 0.0, 0.7601, -0.8927, -0.7623, -0.5406 ];
        $expectedEval = [ 0.0, -1.0, 1.0, -0.2423, -0.0661, -0.022, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.1123, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.022 ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $steinitz = SanExtractor::steinitz(self::$f, new Board(), $movetext);
        $mean = SanExtractor::mean(self::$f, new Board(), $movetext);
        $sd = SanExtractor::sd(self::$f, new Board(), $movetext);
        $eval = SanExtractor::eval(self::$f, new Board(), $movetext);

        $this->assertEqualsWithDelta($expectedSteinitz, $steinitz, 0.0001);
        $this->assertEqualsWithDelta($expectedMean, $mean, 0.0001);
        $this->assertEqualsWithDelta($expectedSd, $sd, 0.0001);
        $this->assertEqualsWithDelta($expectedEval, $eval[4], 0.0001);
    }
}
