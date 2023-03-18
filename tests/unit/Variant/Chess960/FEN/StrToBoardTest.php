<?php

namespace Chess\Tests\Unit\Variant\Chess960\FEN;

use Chess\Variant\Chess960\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class StrToBoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4()
    {
        $startPos = ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ];

        $board = (new StrToBoard(
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1',
            $startPos
        ))->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5()
    {
        $startPos = ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ];

        $board = (new StrToBoard(
            'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2',
            $startPos
        ))->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' r ', ' n ', ' b ', ' q ', ' k ', ' b ', ' n ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' N ', ' B ', ' Q ', ' K ', ' B ', ' N ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Ng3_Nc6_Bc4_d6()
    {
        $startPos = ['Q', 'N', 'B', 'R', 'K', 'B', 'R', 'N' ];

        $board = (new StrToBoard(
            'q1brkbrn/ppp2ppp/2np4/4p3/2B1P3/6N1/PPPP1PPP/QNBRK1R1 w KQkq -',
            $startPos
        ))->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' q ', ' . ', ' b ', ' r ', ' k ', ' b ', ' r ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' n ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' N ', ' B ', ' R ', ' K ', ' . ', ' R ', ' . ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Ng3_Nc6_Bc4_d6_play_O_O()
    {
        $startPos = ['Q', 'N', 'B', 'R', 'K', 'B', 'R', 'N' ];

        $board = (new StrToBoard(
            'q1brkbrn/ppp2ppp/2np4/4p3/2B1P3/6N1/PPPP1PPP/QNBRK1R1 w KQkq -',
            $startPos
        ))->create();

        $board->play('w', 'O-O');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' q ', ' . ', ' b ', ' r ', ' k ', ' b ', ' r ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' n ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' N ', ' B ', ' R ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function e4_e5_Ng3_Nc6_Bc4_d6_O_O()
    {
        $startPos = ['Q', 'N', 'B', 'R', 'K', 'B', 'R', 'N' ];

        $board = (new StrToBoard(
            'q1brkbrn/ppp2ppp/2np4/4p3/2B1P3/6N1/PPPP1PPP/QNBR1RK1 b kq -',
            $startPos
        ))->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' q ', ' . ', ' b ', ' r ', ' k ', ' b ', ' r ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' n ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' B ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' N ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' N ', ' B ', ' R ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $this->assertSame($expected, $array);
    }
}
