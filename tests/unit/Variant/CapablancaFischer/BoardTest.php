<?php

namespace Chess\Tests\Unit\Variant\CapablancaFischer;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\CapablancaFischer\Board;
use Chess\Variant\CapablancaFischer\StartPosition;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function pieces()
    {
        $startPos = (new StartPosition())->create();
        $board = new Board($startPos);
        $pieces = $board->pieces();

        $this->assertSame(40, count($pieces));
    }

    /**
     * @test
     */
    public function castling_rule_ARBBKRQNNC()
    {
        $startPos = ['A', 'R', 'B', 'B', 'K', 'R', 'Q', 'N', 'N', 'C'];

        $castlingRule = (new Board($startPos))->castlingRule;

        $expected = [
            Color::W => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'g1', 'h1', 'i1' ],
                        'attack' => [ 'e1', 'f1', 'g1', 'h1', 'i1' ],
                        'from' => 'e1',
                        'to' => 'i1',
                    ],
                    Castle::LONG => [
                        'free' => [ 'c1', 'd1' ],
                        'attack' => [ 'c1', 'd1', 'e1' ],
                        'from' => 'e1',
                        'to' => 'c1',
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'from' => 'f1',
                        'to' => 'h1',
                    ],
                    Castle::LONG => [
                        'from' => 'b1',
                        'to' => 'd1',
                    ],
                ],
            ],
            Color::B => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'g8', 'h8', 'i8' ],
                        'attack' => [ 'e8', 'f8', 'g8', 'h8', 'i8' ],
                        'from' => 'e8',
                        'to' => 'i8',
                    ],
                    Castle::LONG => [
                        'free' => [ 'c8', 'd8' ],
                        'attack' => [ 'c8', 'd8', 'e8' ],
                        'from' => 'e8',
                        'to' => 'c8',
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'from' => 'f8',
                        'to' => 'h8',
                    ],
                    Castle::LONG => [
                        'from' => 'b8',
                        'to' => 'd8',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $castlingRule->rule);
    }

    /**
     * @test
     */
    public function play_AQRBKRBNNC_e4_e5()
    {
        $startPos = ['A', 'Q', 'R', 'B', 'K', 'R', 'B', 'N', 'N', 'C'];

        $board = new Board($startPos);

        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $expected = [
            7 => [ 'a', 'q', 'r', 'b', 'k', 'r', 'b', 'n', 'n', 'c' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'A', 'Q', 'R', 'B', 'K', 'R', 'B', 'N', 'N', 'C' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }
}
