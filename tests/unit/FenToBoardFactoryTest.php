<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
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

        $fen = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+';
        $board = new ClassicalBoard();

        $board = FenToBoardFactory::create($fen, $board);

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
}
