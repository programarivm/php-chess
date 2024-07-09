<?php

namespace Chess\Tests\Unit\Variant\RacingKings;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\RacingKings\Board;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $board = new Board();

        $this->assertSame(16, count($board->pieces()));
    }

    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' k ', ' r ', ' b ', ' n ', ' N ', ' B ', ' R ', ' K ' ],
            0 => [ ' q ', ' r ', ' b ', ' n ', ' N ', ' B ', ' R ', ' Q ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }
}
