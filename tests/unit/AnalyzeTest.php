<?php

namespace PGNChess\Tests;

use PGNChess\Board;
use PGNChess\Exception\CastlingException;
use PGNChess\Game;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Symbol;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Type\RookType;
use PHPUnit\Framework\TestCase;

class AnalyzeTest extends TestCase
{
    /**
     * @test
     */
    public function invalid_white_short_castling()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function invalid_white_long_castling()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function invalid_black_short_castling()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function invalid_black_long_castling()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function empty_castling_object()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $board = new Board($pieces);
    }

    /**
     * @test
     */
    public function empty_white_castling_object()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => true
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function empty_black_castling_object()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function invalid_white_castling_object_no_castled_property()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function invalid_white_castling_object_no_castling_short_property()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }

    /**
     * @test
     */
    public function invalid_white_castling_object_no_castling_long_property()
    {
        $this->expectException(CastlingException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'f3'), // in check!
            new Pawn(Symbol::BLACK, 'a6'),
            new Pawn(Symbol::BLACK, 'b5'),
            new Pawn(Symbol::BLACK, 'c4'),
            new Knight(Symbol::BLACK, 'd3'),
            new Rook(Symbol::BLACK, 'f5', RookType::CASTLING_SHORT),
            new King(Symbol::BLACK, 'g5'),
            new Pawn(Symbol::BLACK, 'h7')
        ];

        $castling = (object) [
            Symbol::WHITE => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false
            ],
            Symbol::BLACK => (object) [
                'castled' => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);
    }
}
