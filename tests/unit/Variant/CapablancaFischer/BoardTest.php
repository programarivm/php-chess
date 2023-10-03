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
    /*
    |--------------------------------------------------------------------------
    | getPieces()
    |--------------------------------------------------------------------------
    |
    | Gets all pieces.
    |
    */

    /**
     * @test
     */
    public function get_pieces()
    {
        $startPos = (new StartPosition())->create();
        $board = new Board($startPos);
        $pieces = $board->getPieces();

        $this->assertSame(40, count($pieces));
    }

    /*
    |--------------------------------------------------------------------------
    | getCastlingRule()
    |--------------------------------------------------------------------------
    |
    | Returns the castling rule.
    |
    */

    /**
     * @test
     */
    public function get_castling_rule_ARBBKRQNNC()
    {
        $startPos = ['A', 'R', 'B', 'B', 'K', 'R', 'Q', 'N', 'N', 'C'];

        $castlingRule = (new Board($startPos))->getCastlingRule();

        $expected = [
            Color::W => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'g1', 'h1', 'i1' ],
                        'attack' => [ 'e1', 'f1', 'g1', 'h1', 'i1' ],
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'i1',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'c1', 'd1' ],
                        'attack' => [ 'c1', 'd1', 'e1' ],
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'c1',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'f1',
                            'next' => 'h1',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'b1',
                            'next' => 'd1',
                        ],
                    ],
                ],
            ],
            Color::B => [
                Piece::K => [
                    Castle::SHORT => [
                        'free' => [ 'g8', 'h8', 'i8' ],
                        'attack' => [ 'e8', 'f8', 'g8', 'h8', 'i8' ],
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'i8',
                        ],
                    ],
                    Castle::LONG => [
                        'free' => [ 'c8', 'd8' ],
                        'attack' => [ 'c8', 'd8', 'e8' ],
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'c8',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'f8',
                            'next' => 'h8',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'b8',
                            'next' => 'd8',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $castlingRule);
    }

    /*
    |--------------------------------------------------------------------------
    | play()
    |--------------------------------------------------------------------------
    |
    | Legal moves return true.
    |
    */

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
            7 => [ ' a ', ' q ', ' r ', ' b ', ' k ', ' r ', ' b ', ' n ', ' n ', ' c ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ', ' P ', ' P ' ],
            0 => [ ' A ', ' Q ', ' R ', ' B ', ' K ', ' R ', ' B ', ' N ', ' N ', ' C ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }
}
