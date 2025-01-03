<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\SanSignal;
use Chess\Function\FastFunction;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class SanSignalTest extends AbstractUnitTestCase
{
    static private FastFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new FastFunction();
    }

    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5()
    {
        $expectedTime = [ 0.0, 2.0, -1.66, -0.16, -5.0 ];
        $expectedTimeComponentCenter = [ 0.0, 1.0, 0.09, 0.67, -1.0 ];
        $expectedTimeComponentConnectivity = [ 0.0, -1.0, -1.0, -1.0, 1.0 ];
        $expectedTimeComponentSpace = [ 0.0, 1.0, 0.25, 0.50, -1.0 ];

        $expectedSpectrum = [ 0.0, 0.7591, -0.8998, -0.7603, -0.5406 ];
        $expectedSpectrumComponentQxd5 = [ 0.0, -1.0, 1.0, -0.24, -0.07, -0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.11, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.02 ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedTimeComponentCenter, $sanSignal->timeComponent[1]);
        $this->assertEquals($expectedTimeComponentConnectivity, $sanSignal->timeComponent[2]);
        $this->assertEquals($expectedTimeComponentSpace, $sanSignal->timeComponent[3]);

        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
        $this->assertEquals($expectedSpectrumComponentQxd5, $sanSignal->spectrumComponent[4]);
    }

    /**
     * @test
     */
    public function A59()
    {
        $expectedTime = [ 0.0, 2.5, 0.79, 1.02, 1.32, 1.4, 1.7, 3.54, 3.05, 2.79, 0.0, 1.86, 0.18, 0.88, -2.13, 3.42, 2.07, 2.24 ];
        $expectedSpectrum = [ 0.0, 0.3715, 0.8498, 0.809, 0.2435, 0.6693, 0.8366, 0.766, 0.7763, 0.7438, 0.7385, 0.5999, -0.696, -0.6173, -0.5291, 0.5017, 0.5279, 0.5166 ];

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');
        $sanSignal = new SanSignal(self::$f, $A59, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a3()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedSpectrum = [ 0.0, 0.03 ];

        $movetext = '1.a3';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedSpectrum = [ 0.0, 0.23 ];

        $movetext = '1.a4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function b4()
    {
        $expectedTime = [ 0.0, 1.0 ];
        $expectedSpectrum = [ 0.0, 0.9381 ];

        $movetext = '1.b4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function c4()
    {
        $expectedTime = [ 0.0, 1.0 ];
        $expectedSpectrum = [ 0.0, 0.8738 ];

        $movetext = '1.c4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function d4()
    {
        $expectedTime = [ 0.0, 3.0 ];
        $expectedSpectrum = [ 0.0, 0.3715 ];

        $movetext = '1.d4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function e4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedSpectrum = [ 0.0, 0.7591 ];

        $movetext = '1.e4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function f4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedSpectrum = [ 0.0, 0.7348 ];

        $movetext = '1.f4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function g4()
    {
        $expectedTime = [ 0.0, 1.0 ];
        $expectedSpectrum = [ 0.0, 0.9335 ];

        $movetext = '1.g4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function h4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedSpectrum = [ 0.0, 0.2249 ];

        $movetext = '1.h4';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a4_h5()
    {
        $expectedTime = [ 0.0, 2.0, 0.04 ];
        $expectedSpectrum = [ 0.0, 0.23, 0.0 ];

        $movetext = '1.a4 h5';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a4_a5()
    {
        $expectedTime = [ 0.0, 2.0, 0.06 ];
        $expectedSpectrum = [ 0.0, 0.23, 0.0 ];

        $movetext = '1.a4 a5';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a4_e5()
    {
        $expectedTime = [ 0.0, 2.0, -2.0 ];
        $expectedSpectrum = [ 0.0, 0.23, -0.743 ];

        $movetext = '1.a4 e5';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function h4_e5()
    {
        $expectedTime = [ 0.0, 2.0, -3.0 ];
        $expectedSpectrum = [ 0.0, 0.2249, -0.6551 ];

        $movetext = '1.h4 e5';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a5()
    {
        $expectedTime = [ 0.0, 4.0 ];
        $expectedSpectrum = [ 0.0, 0.8632 ];

        $fen = '7k/8/8/8/P7/8/8/7K w - -';
        $movetext = '1.a5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$f, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a6()
    {
        $expectedTime = [ 0.0, 5.0 ];
        $expectedSpectrum = [ 0.0, 0.8292 ];

        $fen = '7k/8/8/P7/8/8/8/7K w - -';
        $movetext = '1.a6';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$f, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function a7()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedSpectrum = [ 0.0, 0.8598 ];

        $fen = '4k3/8/P7/8/8/8/8/4K3 w - -';
        $movetext = '1.a7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$f, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function b5()
    {
        $expectedTime = [ 0.0, 4.0 ];
        $expectedSpectrum = [ 0.0, 0.7101 ];

        $fen = '7k/8/8/8/1P6/8/8/7K w - -';
        $movetext = '1.b5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$f, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }

    /**
     * @test
     */
    public function h7()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedSpectrum = [ 0.0, 0.8575 ];

        $fen = '4k3/8/7P/8/8/8/8/4K3 w - -';
        $movetext = '1.h7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$f, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }
}
