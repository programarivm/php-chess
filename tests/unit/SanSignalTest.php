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
        $expectedTime = [ 0.0, 2.0, -1.66, -0.18, -5.0 ];
        $expectedTimeComponentCenter = [ 0.0, 1.0, 0.09, 0.65, -1.0 ];
        $expectedTimeComponentConnectivity = [ 0.0, -1.0, -1.0, -1.0, 1.0 ];
        $expectedTimeComponentSpace = [ 0.0, 1.0, 0.25, 0.50, -1.0 ];

        $expectedSpectrum = [ 0.0, 0.7576, -0.9017, -0.763, -0.5406 ];
        $expectedSpectrumComponent = [
            [ 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ],
            [ 0.0, 1.0, -1.0, 0.65, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.08, ],
            [ 0.0, 0.55, -1.0, 1.0, 0.0, 0.0, -1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, ],
            [ 0.13, 1.0, -1.0, 0.5, -1.0, 0.0, -1.0, 0.0, 0.0, 0.0, 0.13, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -1.0, 0.0, 0.0, 0.0, 0.0, 0.0, ],
            [ 0.0, -1.0, 1.0, -0.24, -0.07, -0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.11, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -0.02, ],
        ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $sanSignal = new SanSignal(self::$f, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedTimeComponentCenter, $sanSignal->timeComponent[1]);
        $this->assertEquals($expectedTimeComponentConnectivity, $sanSignal->timeComponent[2]);
        $this->assertEquals($expectedTimeComponentSpace, $sanSignal->timeComponent[3]);

        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
        $this->assertEquals($expectedSpectrumComponent, $sanSignal->spectrumComponent);
    }

    /**
     * @test
     */
    public function A59()
    {
        $expectedTime = [ 0.0, 2.5, 0.78, 1.0, 1.32, 1.4, 1.7, 3.53, 3.04, 2.78, 0.0, 1.86, 0.19, 0.89, -2.17, 3.41, 2.05, 2.23 ];
        $expectedSpectrum = [ 0.0, 0.3716, 0.8498, 0.809, 0.246, 0.6693, 0.8381, 0.7675, 0.7772, 0.7443, 0.7395, 0.5904, -0.6954, -0.616, -0.5324, 0.501, 0.5279, 0.5158 ];

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
        $expectedSpectrum = [ 0.0, 0.0 ];

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
        $expectedSpectrum = [ 0.0, 0.25 ];

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
        $expectedSpectrum = [ 0.0, 0.9428 ];

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
        $expectedSpectrum = [ 0.0, 0.8754 ];

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
        $expectedSpectrum = [ 0.0, 0.3716 ];

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
        $expectedSpectrum = [ 0.0, 0.7576 ];

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
        $expectedSpectrum = [ 0.0, 0.7338 ];

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
        $expectedSpectrum = [ 0.0, 0.9223 ];

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
        $expectedSpectrum = [ 0.0, 0.2 ];

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
        $expectedTime = [ 0.0, 2.0, -1.0 ];
        $expectedSpectrum = [ 0.0, 0.25, 0.0 ];

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
        $expectedTime = [ 0.0, 2.0, 0.0 ];
        $expectedSpectrum = [ 0.0, 0.25, 0.0 ];

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
        $expectedSpectrum = [ 0.0, 0.25, -0.7404 ];

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
        $expectedSpectrum = [ 0.0, 0.2, -0.6541 ];

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
        $expectedSpectrum = [ 0.0, 0.866 ];

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
        $expectedSpectrum = [ 0.0, 0.8315 ];

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
        $expectedTime = [ 0.0, 3.0 ];
        $expectedSpectrum = [ 0.0, 0.9035 ];

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
        $expectedSpectrum = [ 0.0, 0.7074 ];

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
        $expectedTime = [ 0.0, 4.0 ];
        $expectedSpectrum = [ 0.0, 0.8485 ];

        $fen = '4k3/8/7P/8/8/8/8/4K3 w - -';
        $movetext = '1.h7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$f, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedSpectrum, $sanSignal->spectrum);
    }
}
