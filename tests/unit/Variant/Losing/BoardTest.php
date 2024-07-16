<?php

namespace Chess\Tests\Unit\Variant\Losing;

use Chess\FenToBoardFactory;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Losing\Board;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $board = new Board();

        $this->assertSame(32, count($board->pieces()));
    }

    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' m ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' M ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e3_d6_Bb5_h6()
    {
        $board = new Board();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' m ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' p ' ],
            4 => [ ' . ', ' B ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' M ', ' . ', ' N ', ' R ' ],
        ];

        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'Bb5'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertFalse($board->isCheck());
        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e3_d6_Bb5_h6_Bxe8()
    {
        $board = new Board();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' B ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' p ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' M ', ' . ', ' N ', ' R ' ],
        ];

        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'Bb5'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('w', 'Bxe8'));
        $this->assertFalse($board->isCheck());
        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e4_d5()
    {
        $board = new Board();

        $expected = ['d5'];

        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertEquals($expected, $board->legal('e4'));
    }
}
