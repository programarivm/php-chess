<?php

namespace Chess\Tests\Unit\Variant\Dunsany;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Dunsany\Board;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $board = new Board();

        $this->assertSame(48, count($board->pieces()));
    }

    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            2 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
        $this->assertSame('b', $board->turn);
    }

    /**
     * @test
     */
    public function play_e6()
    {
        $board = new Board();

        $board->play('b', 'e5');

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            2 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }
}
