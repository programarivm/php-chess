<?php

namespace Chess\Tests\Unit\Variant\Chess960\FEN;

use Chess\Variant\Chess960\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class StrToBoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function RNBQKBNR_e4()
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
    public function RNBQKBNR_e4_e5()
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
    public function QNBRKBRN_e4_e5_Ng3_Nc6_Bc4_d6()
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
    public function QNBRKBRN_e4_e5_Ng3_Nc6_Bc4_d6_O_O()
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
    public function BNNBQRKR_e4_Nc6_d3_Nd6_c3_e6_Nd2_Bf6_Ne2_Qe7()
    {
        $startPos = ['B', 'N', 'N', 'B', 'Q', 'R', 'K', 'R' ];

        $board = (new StrToBoard(
            'b4rkr/ppppqppp/2nnpb2/8/4P3/2PP4/PP1NNPPP/B2BQRKR w KQkq -',
            $startPos
        ))->create();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' b ', ' . ', ' . ', ' . ', ' . ', ' r ', ' k ', ' r ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' q ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' n ', ' n ', ' p ', ' b ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' P ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' . ', ' N ', ' N ', ' P ', ' P ', ' P ' ],
            0 => [ ' B ', ' . ', ' . ', ' B ', ' Q ', ' R ', ' K ', ' R ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function QRKRNNBB_Bf2_Re8_Nd3_O_O_O_O_O()
    {
        $startPos = ['Q', 'R', 'K', 'R', 'N', 'N', 'B', 'B' ];

        $board = (new StrToBoard(
            'qrkr1nbb/pppp2pp/3n1p2/4p3/4P3/4NP2/PPPP2PP/QRKRN1BB w KQkq -',
            $startPos
        ))->create();

        $board->play('w', 'Bf2');
        $board->play('b', 'Re8');
        $board->play('w', 'Nd3');
        $board->play('b', 'O-O-O');
        $board->play('w', 'O-O');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' q ', ' . ', ' k ', ' r ', ' r ', ' n ', ' b ', ' b ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' . ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' n ', ' . ', ' p ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' N ', ' N ', ' P ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' B ', ' P ', ' P ' ],
            0 => [ ' Q ', ' R ', ' . ', ' . ', ' . ', ' R ', ' K ', ' B ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function QNRNBKRB_O_O_undo()
    {
        $startPos = ['Q', 'N', 'R', 'N', 'B', 'K', 'R', 'B' ];

        $board = (new StrToBoard(
            'qnrnbkrb/pppppppp/8/8/8/8/PPPPPPPP/QNRNBKRB w KQkq -',
            $startPos
        ))->create();

        $board->play('w', 'O-O');

        $board = $board->undo();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' q ', ' n ', ' r ', ' n ', ' b ', ' k ', ' r ', ' b ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' N ', ' R ', ' N ', ' B ', ' K ', ' R ', ' B ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function QNRNBKRB_O_O_O_O_undo_undo()
    {
        $startPos = ['Q', 'N', 'R', 'N', 'B', 'K', 'R', 'B' ];

        $board = (new StrToBoard(
            'qnrnbkrb/pppppppp/8/8/8/8/PPPPPPPP/QNRNBKRB w KQkq -',
            $startPos
        ))->create();

        $board->play('w', 'O-O');
        $board->play('b', 'O-O');

        $board = $board->undo();
        $board = $board->undo();

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' q ', ' n ', ' r ', ' n ', ' b ', ' k ', ' r ', ' b ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' N ', ' R ', ' N ', ' B ', ' K ', ' R ', ' B ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function resume_RKNRQNBB_d5()
    {
        $startPos = ['R', 'K', 'N', 'R', 'Q', 'N', 'B', 'B' ];

        $board = (new StrToBoard(
            'rknrqnbb/pppp1ppp/8/4p3/8/4N2P/PPPPPPP1/RKNRQ1BB b KQkq -',
            $startPos
        ))->create();

        $board->play('b', 'd5');

        $expected = '1...d5';

        $this->assertEquals($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function resume_RKNRQNBB_d5_Nd3()
    {
        $startPos = ['R', 'K', 'N', 'R', 'Q', 'N', 'B', 'B' ];

        $board = (new StrToBoard(
            'rknrqnbb/pppp1ppp/8/4p3/8/4N2P/PPPPPPP1/RKNRQ1BB b KQkq -',
            $startPos
        ))->create();

        $board->play('b', 'd5');
        $board->play('w', 'Nd3');

        $expected = '1...d5 2.Nd3';

        $this->assertEquals($expected, $board->getMovetext());
    }
}
