<?php

namespace Chess\Tests\Unit;

use Chess\SanSignal;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class SanSignalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5()
    {
        $movetext = '1.e4 d5 2.exd5 Qxd5';

        $board = new Board();

        $balance = (new SanSignal($movetext, $board))->balance;

        $expected = [ 0, 1.0, 0.25, 0.50, -1.0 ];

        $this->assertSame($expected, $balance[3]);
    }
}
