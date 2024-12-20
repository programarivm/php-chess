<?php

namespace Chess\Tests\Unit;

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
        $expectedBalance = [ 0, 1.0, 0.25, 0.50, -1.0 ];
        $expectedSignal = [ 0.0, 2.0, -1.67, -0.16, -5.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $movetext = '1.e4 d5 2.exd5 Qxd5';

        $sanSignal = new SanSignal(self::$function, $movetext, new Board());

        $this->assertEquals($expectedBalance, $sanSignal->balance[3]);
        $this->assertEquals($expectedSignal, $sanSignal->signal);
    }

    /**
     * @test
     */
    public function A59()
    {
        $expectedSignal = [ 0.0, 2.5, 0.77, 1.01, 1.31, 1.4, 1.69, 3.52, 3.03, 2.77, 0.0, 1.85, 0.17, 0.88, -2.13, 3.42, 2.07, 2.24, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0 ];

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $sanSignal = new SanSignal(self::$function, $A59, new Board());

        $this->assertEquals($expectedSignal, $sanSignal->signal);
    }
}
