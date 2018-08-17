<?php

namespace PGNChess\Tests\Unit\Board;

use PGNChess\Board;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\Type\RookType;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * @test
     */
    public function count_squares_in_new_board()
    {
        $board = new Board;

        $this->assertEquals(count($board), 32);
        $this->assertEquals(count($board->getSquares()->used->w), 16);
        $this->assertEquals(count($board->getSquares()->used->b), 16);
    }

    /**
     * @test
     */
    public function count_squares_in_custom_board()
    {
        $pieces = [
            new Bishop(Symbol::WHITE, 'c1'),
            new Queen(Symbol::WHITE, 'd1'),
            new King(Symbol::WHITE, 'e1'),
            new Pawn(Symbol::WHITE, 'e2'),
            new King(Symbol::BLACK, 'e8'),
            new Bishop(Symbol::BLACK, 'f8'),
            new Knight(Symbol::BLACK, 'g8')
        ];

        $castling = (object) [
            'w' => (object) [
                'castled' => false,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => (object) [
                'castled' => false,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $this->assertEquals(count($board), 7);
        $this->assertEquals(count($board->getSquares()->used->w), 4);
        $this->assertEquals(count($board->getSquares()->used->b), 3);
    }

    /**
     * @test
     */
    public function play_some_moves_and_check_castling()
    {
        $board = new Board;

        $board->play(Convert::toObject(Symbol::WHITE, 'd4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'c6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bf4'));
        $board->play(Convert::toObject(Symbol::BLACK, 'd5'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Nc3'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Nf6'));
        $board->play(Convert::toObject(Symbol::WHITE, 'Bxb8'));
        $board->play(Convert::toObject(Symbol::BLACK, 'Rxb8'));

        $castling = (object) [
            'w' => (object) [
                'castled' => false,
                'O-O' => true,
                'O-O-O' => true
            ],
            'b' => (object) [
                'castled' => false,
                'O-O' => true,
                'O-O-O' => false
            ]
        ];

        $this->assertEquals($castling, $board->getCastling());
    }

    /**
     * @test
     */
    public function play_some_moves_and_check_space()
    {
        $game = [
            'e4 e5',
            'f4 exf4',
            'd4 Nf6',
            'Nc3 Bb4',
            'Bxf4 Bxc3+'
        ];

        $board = new Board;

        foreach ($game as $entry)
        {
            $moves = explode(' ', $entry);
            $board->play(Convert::toObject(Symbol::WHITE, $moves[0]));
            $board->play(Convert::toObject(Symbol::BLACK, $moves[1]));
        }

        $space = (object) [
            'w' => [
                'a3',
                'a6',
                'b1',
                'b3',
                'b5',
                'c1',
                'c4',
                'c5',
                'd2',
                'd3',
                'd5',
                'd6',
                'e2',
                'e3',
                'e5',
                'f2',
                'f3',
                'f5',
                'g3',
                'g4',
                'g5',
                'h3',
                'h5',
                'h6'
            ],
            'b' => [
                'a5',
                'a6',
                'b4',
                'b6',
                'c6',
                'd2',
                'd5',
                'd6',
                'e6',
                'e7',
                'f8',
                'g4',
                'g6',
                'g8',
                'h5',
                'h6'
            ]
        ];

        $this->assertEquals($space, $board->getControl()->space);
    }

    /**
     * @test
     */
    public function captures()
    {
        $captures= (object) [
            'w' => [
                (object) [
                'capturing' => (object) [
                    'identity' => 'P',
                    'position' => 'b2'],
                'captured' => (object) [
                    'identity' => 'B',
                    'position' => 'c3']
                ],
                (object) [
                'capturing' => (object) [
                    'identity' => 'P',
                    'position' => 'e5'],
                'captured' => (object) [
                    'identity' => 'P',
                    'position' => 'f5'],
                ],
                (object) [
                'capturing' => (object) [
                    'identity' => 'P',
                    'position' => 'd4'],
                'captured' => (object) [
                    'identity' => 'P',
                    'position' => 'c5']
                ]
            ],
            'b' => [
                (object) [
                'capturing' => (object) [
                    'identity' => 'B',
                    'position' => 'b4'],
                'captured' => (object) [
                    'identity' => 'N',
                    'position' => 'c3']
                ],
                (object) [
                'capturing' => (object) [
                    'identity' => 'R',
                    'position' => 'f8',
                    'type' => 1],
                'captured' => (object) [
                    'identity' => 'P',
                    'position' => 'f6'],
                ]
            ]
        ];

        $board = new Board;

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'd5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nc3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bb4')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'c5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qg4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Ne7')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nf3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nbc6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'a3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bxc3')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'bxc3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Qc7')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rb1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'O-O')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bd3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'f5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'exf6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rxf6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qh3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'g6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qh4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rf7')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qg3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Qa5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ng5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rg7')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'dxc5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e5')));

        $this->assertEquals($captures, $board->getCaptures());
    }

    /**
     * @test
     */
    public function kings_legal_moves_when_moved_and_not_castled()
    {
        $kingsLegalMoves = [
            'e8',
            'd7',
            'd8',
            'f8',
            'd6',
            'f6'
        ];

        $board = new Board;

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'e6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'd4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'd5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nc3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bb4')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ne2')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nf6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'a3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Be7')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'exd5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nxd5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nxd5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'exd5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Ng3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'g6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bh6')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Be6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bd3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Nc6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'O-O')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bf6')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'c3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Ne7')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Qb3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Qc8')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Rae1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Ng8')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bc1')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Ne7')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'f4')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bh4')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'f5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Bxg3')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'hxg3')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'gxf5')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bg5')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Rg8')));

        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Bxe7')));
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::BLACK, 'Kxe7')));

        $king = $board->getPieceByPosition('e7');

        $this->assertEquals($kingsLegalMoves, $king->getLegalMoves());
    }
}
