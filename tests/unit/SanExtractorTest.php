<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\SanExtractor;
use Chess\Function\FastFunction;
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
        $expectedMean = [ 0.0, 0.18635, -0.12213, -0.28161, -0.06688 ];
        $expectedSd = [ 0.0, 0.75943, -0.8947, -0.76196, -0.54069 ];
        $expectedEval = [ 0.0, -1.0, 1.0, -0.24406, -0.06656, -0.02219, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.11315, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.02219 ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $mean = SanExtractor::mean(self::$f, new Board(), $movetext);
        $sd = SanExtractor::sd(self::$f, new Board(), $movetext);
        $eval = SanExtractor::eval(self::$f, new Board(), $movetext);

        $this->assertEqualsWithDelta($expectedMean, $mean, 0.0001);
        $this->assertEqualsWithDelta($expectedSd, $sd, 0.0001);
        $this->assertEqualsWithDelta($expectedEval, $eval[4], 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a3()
    {
        $expectedSd = [ 0.0, 0.00316 ];

        $movetext = '1.a3';
        $sd = SanExtractor::sd(self::$f, new Board(), $movetext);

        $this->assertEqualsWithDelta($expectedSd, $sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a4()
    {
        $expectedSd = [ 0.0, 0.24625 ];

        $movetext = '1.a4';
        $sd = SanExtractor::sd(self::$f, new Board(), $movetext);

        $this->assertEqualsWithDelta($expectedSd, $sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function h4()
    {
        $expectedSd = [ 0.0, 0.24125 ];

        $movetext = '1.h4';
        $sd = SanExtractor::sd(self::$f, new Board(), $movetext);

        $this->assertEqualsWithDelta($expectedSd, $sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a4_h5()
    {
        $expectedSd = [ 0.0, 0.24625, 0.0 ];

        $movetext = '1.a4 h5';
        $sd = SanExtractor::sd(self::$f, new Board(), $movetext);

        $this->assertEqualsWithDelta($expectedSd, $sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a4_a5()
    {
        $expectedSd = [ 0.0, 0.24625, 0.0 ];

        $movetext = '1.a4 a5';
        $sd = SanExtractor::sd(self::$f, new Board(), $movetext);

        $this->assertEqualsWithDelta($expectedSd, $sd, 0.0001);
    }
}
