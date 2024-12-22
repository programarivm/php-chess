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
        $expectedCenter = [ 0.0, 1.0, 0.08, 0.67, -1.0 ];
        $expectedConnectivity = [ 0.0, -1.0, -1.0, -1.0, 1.0 ];
        $expectedSpace = [ 0.0, 1.0, 0.25, 0.50, -1.0 ];
        $expectedUnnormalized = [ 0.0, 20.0, 1.0, 10.0, -63.5 ];
        $expectedNormalized = [ 0.0, 2.0, -1.67, -0.16, -5.0 ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedCenter, $sanSignal->balance[1]);
        $this->assertEquals($expectedConnectivity, $sanSignal->balance[2]);
        $this->assertEquals($expectedSpace, $sanSignal->balance[3]);
        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function A59()
    {
        $expectedUnnormalized = [ 0.0, 18.0, 2.6, 11.6, 4.6, 5.6, 1.6, 6.6, 4.6, 2.6, -7.4, 3.0, -2.0, 7.0, -4.1, 12.9, 8.9, 9.9 ];
        $expectedNormalized = [ 0.0, 2.5, 0.77, 1.01, 1.31, 1.4, 1.69, 3.52, 3.03, 2.77, 0.0, 1.85, 0.17, 0.88, -2.13, 3.42, 2.07, 2.24 ];

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');
        $sanSignal = new SanSignal(self::$function, $A59, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a3()
    {
        $expectedUnnormalized = [ 0.0, 2.0 ];
        $expectedNormalized = [ 0.0, 2.0 ];

        $movetext = '1.a3';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a4()
    {
        $expectedUnnormalized = [ 0.0, 3.0 ];
        $expectedNormalized = [ 0.0, 2.0 ];

        $movetext = '1.a4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function b4()
    {
        $expectedUnnormalized = [ 0.0, 5.0 ];
        $expectedNormalized = [ 0.0, 1.0 ];

        $movetext = '1.b4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function c4()
    {
        $expectedUnnormalized = [ 0.0, 9.0 ];
        $expectedNormalized = [ 0.0, 1.0 ];

        $movetext = '1.c4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function d4()
    {
        $expectedUnnormalized = [ 0.0, 18.0 ];
        $expectedNormalized = [ 0.0, 3.0 ];

        $movetext = '1.d4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function e4()
    {
        $expectedUnnormalized = [ 0.0, 20.0 ];
        $expectedNormalized = [ 0.0, 2.0 ];

        $movetext = '1.e4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function f4()
    {
        $expectedUnnormalized = [ 0.0, 9.0 ];
        $expectedNormalized = [ 0.0, 2.0 ];

        $movetext = '1.f4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function g4()
    {
        $expectedUnnormalized = [ 0.0, 5.0 ];
        $expectedNormalized = [ 0.0, 1.0 ];

        $movetext = '1.g4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function h4()
    {
        $expectedUnnormalized = [ 0.0, 3.0 ];
        $expectedNormalized = [ 0.0, 2.0 ];

        $movetext = '1.h4';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a4_h5()
    {
        $expectedUnnormalized = [ 0.0, 3.0, 0.0 ];
        $expectedNormalized = [ 0.0, 2.0, 0.0 ];

        $movetext = '1.a4 h5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a4_a5()
    {
        $expectedUnnormalized = [ 0.0, 3.0, 0.0 ];
        $expectedNormalized = [ 0.0, 2.0, 0.0 ];

        $movetext = '1.a4 a5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a4_e5()
    {
        $expectedUnnormalized = [ 0.0, 3.0, -17.0 ];
        $expectedNormalized = [ 0.0, 2.0, -2.0 ];

        $movetext = '1.a4 e5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function h4_e5()
    {
        $expectedUnnormalized = [ 0.0, 3.0, -17.0 ];
        $expectedNormalized = [ 0.0, 2.0, -3.0 ];

        $movetext = '1.h4 e5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a6()
    {
        $expectedUnnormalized = [ 0.0, 5.0 ];
        $expectedNormalized = [ 0.0, 5.0 ];

        $fen = '7k/8/8/P7/8/8/8/7K w - -';
        $movetext = '1.a6';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a7()
    {
        $expectedUnnormalized = [ 0.0, 3.0 ];
        $expectedNormalized = [ 0.0, 3.0 ];

        $fen = '7k/8/P7/8/8/8/8/7K w - -';
        $movetext = '1.a7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }
}
