<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
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
            new Pawn('w', 'a2'),
            new Pawn('w', 'a3'),
            new Pawn('w', 'c3'),
            new Rook('w', 'e6', RookType::CASTLING_LONG),
            new King('w', 'g3'),
            new Pawn('b', 'a6'),
            new Pawn('b', 'b5'),
            new Pawn('b', 'c4'),
            new Knight('b', 'd3'),
            new Rook('b', 'f5', RookType::CASTLING_SHORT),
            new King('b', 'g5'),
            new Pawn('b', 'h7')
        ];

        $castling = [
            'w' => [
                'castled' => true,
                'O-O' => false,
                'O-O-O' => false
            ],
            'b' => [
                'castled' => true,
                'O-O' => false,
                'O-O-O' => false
            ]
        ];

        (new Board($pieces, $castling))
            ->play('w', 'f4');
    }
}
