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
        $expectedUnnormalized = [ 0.0, 20.3, 1.1, 10.0, -64.0 ];
        $expectedNormalized = [ 0.0, 2.0, -1.66, -0.18, -5.0 ];

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
        $expectedUnnormalized = [ 0.0, 18.3, 2.48, 11.48, 4.48, 5.48, 1.48, 6.48, 4.48, 2.48, -7.52, 2.98, -2.12, 7.08, -4.02, 13.08, 8.98, 9.98 ];
        $expectedNormalized = [ 0.0, 2.5, 0.75, 0.98, 1.3, 1.38, 1.67, 3.5, 3.01, 2.75, 0.0, 1.85, 0.16, 0.87, -2.13, 3.41, 2.05, 2.22 ];

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
        $expectedUnnormalized = [ 0.0, 18.3 ];
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
        $expectedUnnormalized = [ 0.0, 20.3 ];
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
        $expectedUnnormalized = [ 0.0, 9.3 ];
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
        $expectedUnnormalized = [ 0.0, 5.2 ];
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
        $expectedUnnormalized = [ 0.0, 3.1 ];
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
        $expectedUnnormalized = [ 0.0, 3.0, -0.1 ];
        $expectedNormalized = [ 0.0, 2.0, -1.0 ];

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
        $expectedUnnormalized = [ 0.0, 3.0, -17.3 ];
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
        $expectedUnnormalized = [ 0.0, 3.1, -17.2 ];
        $expectedNormalized = [ 0.0, 2.0, -3.0 ];

        $movetext = '1.h4 e5';
        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function a5()
    {
        $expectedUnnormalized = [ 0.0, 4.0 ];
        $expectedNormalized = [ 0.0, 4.0 ];

        $fen = '7k/8/8/8/P7/8/8/7K w - -';
        $movetext = '1.a5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

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

    /**
     * @test
     */
    public function b5()
    {
        $expectedUnnormalized = [ 0.0, 8.0 ];
        $expectedNormalized = [ 0.0, 4.0 ];

        $fen = '7k/8/8/8/1P6/8/8/7K w - -';
        $movetext = '1.b5';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }

    /**
     * @test
     */
    public function h7()
    {
        $expectedUnnormalized = [ 0.0, 3.0 ];
        $expectedNormalized = [ 0.0, 3.0 ];

        $fen = 'k7/8/7P/8/8/8/8/K7 w - -';
        $movetext = '1.h7';
        $board = FenToBoardFactory::create($fen);
        $sanSignal = new SanSignal(self::$function, $movetext, $board);

        $this->assertEquals($expectedUnnormalized, $sanSignal->unnormalized);
        $this->assertEquals($expectedNormalized, $sanSignal->normalized);
    }
}
