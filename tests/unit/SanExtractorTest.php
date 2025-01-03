<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\SanExtractor;
use Chess\Function\FastFunction;
use Chess\Play\SanPlay;
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
        $expectedHeuristic = [ 0.0, 0.7591, -0.8998, -0.7603, -0.5406 ];
        $expectedHeuristicComponentQxd5 = [ 0.0, -1.0, 1.0, -0.24, -0.07, -0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.11, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.02 ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
        $this->assertEqualsWithDelta($expectedHeuristicComponentQxd5, $sanSignal->heuristic[4], 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function A59()
    {
        $expectedHeuristic = [ 0.0, 0.3715, 0.8498, 0.809, 0.2435, 0.6693, 0.8366, 0.766, 0.7763, 0.7438, 0.7385, 0.5999, -0.696, -0.6173, -0.5291, 0.5017, 0.5279, 0.5166 ];

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');
        $sanSignal = new SanExtractor(self::$f, $A59, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a3()
    {
        $expectedHeuristic = [ 0.0, 0.03 ];

        $movetext = '1.a3';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a4()
    {
        $expectedHeuristic = [ 0.0, 0.23 ];

        $movetext = '1.a4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function b4()
    {
        $expectedHeuristic = [ 0.0, 0.9381 ];

        $movetext = '1.b4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function c4()
    {
        $expectedHeuristic = [ 0.0, 0.8738 ];

        $movetext = '1.c4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function d4()
    {
        $expectedHeuristic = [ 0.0, 0.3715 ];

        $movetext = '1.d4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function e4()
    {
        $expectedHeuristic = [ 0.0, 0.7591 ];

        $movetext = '1.e4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function f4()
    {
        $expectedHeuristic = [ 0.0, 0.7348 ];

        $movetext = '1.f4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function g4()
    {
        $expectedHeuristic = [ 0.0, 0.9335 ];

        $movetext = '1.g4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function h4()
    {
        $expectedHeuristic = [ 0.0, 0.2249 ];

        $movetext = '1.h4';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a4_h5()
    {
        $expectedHeuristic = [ 0.0, 0.23, 0.0 ];

        $movetext = '1.a4 h5';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a4_a5()
    {
        $expectedHeuristic = [ 0.0, 0.23, 0.0 ];

        $movetext = '1.a4 a5';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a4_e5()
    {
        $expectedHeuristic = [ 0.0, 0.23, -0.743 ];

        $movetext = '1.a4 e5';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function h4_e5()
    {
        $expectedHeuristic = [ 0.0, 0.2249, -0.6551 ];

        $movetext = '1.h4 e5';
        $sanSignal = new SanExtractor(self::$f, $movetext, new Board());

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a5()
    {
        $expectedHeuristic = [ 0.0, 0.8632 ];

        $fen = '7k/8/8/8/P7/8/8/7K w - -';
        $movetext = '1.a5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanExtractor(self::$f, $movetext, $board);

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a6()
    {
        $expectedHeuristic = [ 0.0, 0.8292 ];

        $fen = '7k/8/8/P7/8/8/8/7K w - -';
        $movetext = '1.a6';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanExtractor(self::$f, $movetext, $board);

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function a7()
    {
        $expectedHeuristic = [ 0.0, 0.8598 ];

        $fen = '4k3/8/P7/8/8/8/8/4K3 w - -';
        $movetext = '1.a7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanExtractor(self::$f, $movetext, $board);

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function b5()
    {
        $expectedHeuristic = [ 0.0, 0.7101 ];

        $fen = '7k/8/8/8/1P6/8/8/7K w - -';
        $movetext = '1.b5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanExtractor(self::$f, $movetext, $board);

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }

    /**
     * @test
     * @requires PHP 8.4
     */
    public function h7()
    {
        $expectedHeuristic = [ 0.0, 0.8575 ];

        $fen = '4k3/8/7P/8/8/8/8/4K3 w - -';
        $movetext = '1.h7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanExtractor(self::$f, $movetext, $board);

        $this->assertEqualsWithDelta($expectedHeuristic, $sanSignal->sd, 0.0001);
    }
}
