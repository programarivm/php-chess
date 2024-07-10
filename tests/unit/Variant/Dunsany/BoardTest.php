<?php

namespace Chess\Tests\Unit\Variant\Dunsany;

use Chess\FenToBoardFactory;
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
    public function play_e5()
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

    /**
     * @test
     */
    public function play_Nc6_d5()
    {
        $board = new Board();

        $expected = '1...Nc6 2.d5 e5';

        $this->assertTrue($board->play('b', 'Nc6'));
        $this->assertTrue($board->play('w', 'd5'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertFalse($board->play('w', 'a6'));
        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function play_lan_e7e6()
    {
        $board = new Board();
        $board->playLan('b', 'e7e6');

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            2 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }

    /**
     * @test
     */
    public function b_wins()
    {
        $board = FenToBoardFactory::create(
            '5k2/2r5/8/8/2P5/8/8/8 b - -',
            new Board()
        );

        $this->assertSame('b', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertTrue($board->play('b', 'Rxc4'));
        $this->assertSame('w', $board->turn);
        $this->assertFalse($board->isStalemate());
    }
}
