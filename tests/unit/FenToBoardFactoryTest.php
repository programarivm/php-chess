<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\RacingKings\Board as RacingKingsBoard;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function classical_en_passant_b4_e6_b5_c5_bxc6()
    {
        $board = FenToBoardFactory::create('rnbqkbnr/pp1p1ppp/4p3/1Pp5/8/8/P1PPPPPP/RNBQKBNR w KQkq c6');

        $board->play('w', 'bxc6');

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', '.', 'p', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', 'P', '.', 'p', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', '.', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function classical_en_passant_bxc6()
    {
        $board = FenToBoardFactory::create(
            'r1bqkbnr/p3pppp/8/1Pp5/8/8/P1PPPPPP/RNBQKBNR w KQkq c6 0 1',
            new ClassicalBoard()
        );

        $board->play('w', 'bxc6');

        $expected = [
            7 => [ 'r', '.', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', '.', '.', '.', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', 'P', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', '.', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function racing_kings_Rg8_Nxf2_Qd5()
    {
        $board = FenToBoardFactory::create(
            '6R1/8/8/8/8/8/krbnNn1K/qrb1NBRQ w - -',
            new RacingKingsBoard()
        );

        $expected = [
            7 => [ '.', '.', '.', '.', '.', '.', 'R', '.' ],
            6 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'k', 'r', 'b', 'n', 'N', 'n', '.', 'K' ],
            0 => [ 'q', 'r', 'b', '.', 'N', 'B', 'R', 'Q' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }
}
