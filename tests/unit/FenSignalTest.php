<?php

namespace Chess\Tests\Unit;

use Chess\FenSignal;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class FenSignalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A74()
    {
        $expected = [
            6984,
            1392,
            6978,
            132714,
            258432,
            -48,
            1944,
            53624864,
            2022,
            7044,
            95632,
            95632,
            95632,
            93994,
            84088,
            12146824,
            95632,
            95632,
            94618,
        ];

        $A74 = file_get_contents(self::DATA_FOLDER.'/sample/A74.pgn');

        $board = new Board();

        $signal = (new FenSignal($A74, $board))->signal;

        $this->assertSame($expected, $signal);
    }
}
