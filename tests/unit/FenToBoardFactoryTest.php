<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Classical\Board as ClassicalBoard;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foo_throws_exception()
    {
        $this->expectException(UnknownNotationException::class);

        $fen = 'foo';
        $board = new ClassicalBoard();

        $board = FenToBoardFactory::create($fen, $board);
    }

    /**
     * @test
     */
    public function kaufman_01_Qg4_a5()
    {
        $board = FenToBoardFactory::create(
            '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+',
            new ClassicalBoard()
        );

        $board->play('w', 'Qg4');
        $board->play('b', 'a5');

        $array = $board->toAsciiArray();

        $expected = [
            7 => [ ' . ', ' r ', ' b ', ' q ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' . ', ' . ', ' b ', ' . ', ' n ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' p ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            4 => [ ' p ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' B ', ' . ', ' p ', ' N ', ' . ', ' Q ', ' . ' ],
            2 => [ ' P ', ' . ', ' . ', ' B ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' . ', ' . ', ' R ', ' . ', ' . ', ' R ', ' . ', ' K ' ],
        ];

        $this->assertSame($expected, $array);
    }

    /**
     * @test
     */
    public function QNBRKBRN_e4_e5_Ng3_Nc6_Bc4_d6()
    {
        $board = FenToBoardFactory::create(
            'q1brkbrn/ppp2ppp/2np4/4p3/2B1P3/6N1/PPPP1PPP/QNBRK1R1 w KQkq -',
            new Chess960Board(['Q', 'N', 'B', 'R', 'K', 'B', 'R', 'N' ])
        );

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
}
