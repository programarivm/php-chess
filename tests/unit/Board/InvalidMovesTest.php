<?php

namespace PGNChess\Tests\Unit\Board;

use PGNChess\Board;
use PGNChess\Exception\BoardException;
use PGNChess\Exception\UnknownNotationException;
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

class InvalidMovesTest extends TestCase
{
    /**
     * @test
     */
    public function numeric_value()
    {
        $this->expectException(UnknownNotationException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 9)));
    }

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(UnknownNotationException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'foo')));
    }

    /**
     * @test
     */
    public function bar()
    {
        $this->expectException(UnknownNotationException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'bar')));
    }

    /**
     * @test
     */
    public function e9()
    {
        $this->expectException(UnknownNotationException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e9')));
    }

    /**
     * @test
     */
    public function e10()
    {
        $this->expectException(UnknownNotationException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'e10')));
    }

    /**
     * @test
     */
    public function Nw3()
    {
        $this->expectException(UnknownNotationException::class);
        $board = new Board;
        $this->assertEquals(true, $board->play(Convert::toObject(Symbol::WHITE, 'Nw3')));
    }

    /**
     * @test
     */
    public function piece_does_not_exist()
    {
        $this->expectException(BoardException::class);

        $pieces = [
            new Pawn(Symbol::WHITE, 'a2'),
            new Pawn(Symbol::WHITE, 'a3'),
            new Pawn(Symbol::WHITE, 'c3'),
            new Rook(Symbol::WHITE, 'e6', RookType::CASTLING_LONG),
            new King(Symbol::WHITE, 'g3'),
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
                Symbol::CASTLING_LONG => false
            ]
        ];

        $board = new Board($pieces, $castling);

        $board->play(Convert::toObject(Symbol::WHITE, 'f4'));
    }
}
