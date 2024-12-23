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
    static private FastFunction $function;

    public static function setUpBeforeClass(): void
    {
        self::$function = new FastFunction();
    }

    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5()
    {
        $expectedCenter = [ 0, 1.0, 0.09, 0.65, -1.0 ];
        $expectedConnectivity = [ 0.0, -1.0, -1.0, -1.0, 1.0 ];
        $expectedSpace = [ 0.0, 1.0, 0.25, 0.50, -1.0 ];
        $expectedTime = [ 0.0, 2.0, -1.66, -0.18, -5.0 ];
        $expectedFreq = [ 0.0, 0.73, -0.45, -2.24, -0.46 ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedCenter, $sanSignal->balance[1]);
        $this->assertEquals($expectedConnectivity, $sanSignal->balance[2]);
        $this->assertEquals($expectedSpace, $sanSignal->balance[3]);
        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function A59()
    {
        $expectedTime = [ 0.0, 2.5, 0.78, 1.0, 1.32, 1.4, 1.7, 3.53, 3.04, 2.78, 0.0, 1.86, 0.19, 0.89, -2.17, 3.41, 2.05, 2.23 ];
        $expectedFreq = [ 0.0, 1.62, 0.5, 1.12, 2.35, 1.23, 1.7, 2.2, 0.7, 3.67, 3.88, 1.2, -0.95, -0.55, -0.72, 0.3, 0.29, 0.3 ];

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');
        $sanSignal = new SanSignal(self::$function, $A59, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a3()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedFreq = [ 0.0, 2.0 ];

        $movetext = '1.a3';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedFreq = [ 0.0, 1.5 ];

        $movetext = '1.a4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function b4()
    {
        $expectedTime = [ 0.0, 1.0 ];
        $expectedFreq = [ 0.0, 1.0 ];

        $movetext = '1.b4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function c4()
    {
        $expectedTime = [ 0.0, 1.0 ];
        $expectedFreq = [ 0.0, 0.67 ];

        $movetext = '1.c4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function d4()
    {
        $expectedTime = [ 0.0, 3.0 ];
        $expectedFreq = [ 0.0, 1.62 ];

        $movetext = '1.d4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function e4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedFreq = [ 0.0, 0.73 ];

        $movetext = '1.e4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function f4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedFreq = [ 0.0, 0.64 ];

        $movetext = '1.f4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function g4()
    {
        $expectedTime = [ 0.0, 1.0 ];
        $expectedFreq = [ 0.0, 0.91 ];

        $movetext = '1.g4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function h4()
    {
        $expectedTime = [ 0.0, 2.0 ];
        $expectedFreq = [ 0.0, 1.6 ];

        $movetext = '1.h4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a4_h5()
    {
        $expectedTime = [ 0.0, 2.0, -1.0 ];
        $expectedFreq = [ 0.0, 1.5, -1.0 ];

        $movetext = '1.a4 h5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a4_a5()
    {
        $expectedTime = [ 0.0, 2.0, 0.0 ];
        $expectedFreq = [ 0.0, 1.5, 0.0 ];

        $movetext = '1.a4 a5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a4_e5()
    {
        $expectedTime = [ 0.0, 2.0, -2.0 ];
        $expectedFreq = [ 0.0, 1.5, -0.62 ];

        $movetext = '1.a4 e5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function h4_e5()
    {
        $expectedTime = [ 0.0, 2.0, -3.0 ];
        $expectedFreq = [ 0.0, 1.6, -0.63 ];

        $movetext = '1.h4 e5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a5()
    {
        $expectedTime = [ 0.0, 4.0 ];
        $expectedFreq = [ 0.0, 4.0 ];

        $fen = '7k/8/8/8/P7/8/8/7K w - -';
        $movetext = '1.a5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a6()
    {
        $expectedTime = [ 0.0, 5.0 ];
        $expectedFreq = [ 0.0, 5.0 ];

        $fen = '7k/8/8/P7/8/8/8/7K w - -';
        $movetext = '1.a6';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function a7()
    {
        $expectedTime = [ 0.0, 3.0 ];
        $expectedFreq = [ 0.0, 3.0 ];

        $fen = '4k3/8/P7/8/8/8/8/4K3 w - -';
        $movetext = '1.a7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function b5()
    {
        $expectedTime = [ 0.0, 4.0 ];
        $expectedFreq = [ 0.0, 1.33 ];

        $fen = '7k/8/8/8/1P6/8/8/7K w - -';
        $movetext = '1.b5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }

    /**
     * @test
     */
    public function h7()
    {
        $expectedTime = [ 0.0, 4.0 ];
        $expectedFreq = [ 0.0, 3.2 ];

        $fen = '4k3/8/7P/8/8/8/8/4K3 w - -';
        $movetext = '1.h7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedTime, $sanSignal->time);
        $this->assertEquals($expectedFreq, $sanSignal->freq);
    }
}
