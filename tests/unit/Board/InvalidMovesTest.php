<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
use Chess\Castling\Rule as CastlingRule;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;
use Chess\Tests\AbstractUnitTestCase;

class InvalidMovesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function numeric_value()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 9);
    }

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'foo');
    }

    /**
     * @test
     */
    public function bar()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'bar');
    }

    /**
     * @test
     */
    public function e9()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'e9');
    }

    /**
     * @test
     */
    public function e10()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'e10');
    }

    /**
     * @test
     */
    public function Nw3()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'Nw3');
    }

    /**
     * @test
     */
    public function piece_does_not_exist()
    {
        $this->expectException(\Chess\Exception\BoardException::class);

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

        $castling = [
            Symbol::WHITE => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ],
            Symbol::BLACK => [
                CastlingRule::IS_CASTLED => true,
                Symbol::CASTLING_SHORT => false,
                Symbol::CASTLING_LONG => false
            ]
        ];

        (new Board($pieces, $castling))
            ->play('w', 'f4');
    }
}
