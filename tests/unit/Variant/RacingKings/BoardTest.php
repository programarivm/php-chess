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

    /**
     * @test
     */
    public function play_Rg8_Nf3()
    {
        $board = new Board();

        $expected = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' R ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' k ', ' r ', ' b ', ' n ', ' N ', ' B ', ' . ', ' K ' ],
            0 => [ ' q ', ' r ', ' b ', ' n ', ' N ', ' B ', ' R ', ' Q ' ],
        ];

        $this->assertTrue($board->play('w', 'Rg8'));
        $this->assertFalse($board->play('b', 'Nf3'));
        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function play_Rg8_Nxf2()
    {
        $board = new Board();

        $expected = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' R ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' k ', ' r ', ' b ', ' n ', ' N ', ' n ', ' . ', ' K ' ],
            0 => [ ' q ', ' r ', ' b ', ' . ', ' N ', ' B ', ' R ', ' Q ' ],
        ];

        $this->assertTrue($board->play('w', 'Rg8'));
        $this->assertTrue($board->play('b', 'Nxf2'));
        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function play_Rg8_Nxf2_Qd5()
    {
        $board = new Board();

        $expected = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' R ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' k ', ' r ', ' b ', ' n ', ' N ', ' n ', ' . ', ' K ' ],
            0 => [ ' q ', ' r ', ' b ', ' . ', ' N ', ' B ', ' R ', ' Q ' ],
        ];

        $this->assertTrue($board->play('w', 'Rg8'));
        $this->assertTrue($board->play('b', 'Nxf2'));
        $this->assertFalse($board->play('w', 'Qd5'));
        $this->assertSame($expected, $board->toAsciiArray());
    }
}
