<?php

namespace Chess\Tests\Unit;

use Chess\Board;
use Chess\Castling\Rule as CastlingRule;
use Chess\PGN\Symbol;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\RuyLopez\Open as OpenRuyLopez;

class CastlingTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function white_long()
    {
        $rule = CastlingRule::color(Symbol::WHITE);

        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['squares']['b'], 'b1');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['squares']['c'], 'c1');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['squares']['d'], 'd1');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['position']['current'], 'e1');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['position']['next'], 'c1');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_LONG]['position']['current'], 'a1');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_LONG]['position']['next'], 'd1');
    }

    /**
     * @test
     */
    public function black_long()
    {
        $rule = CastlingRule::color(Symbol::BLACK);

        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['squares']['b'], 'b8');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['squares']['c'], 'c8');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['squares']['d'], 'd8');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['position']['current'], 'e8');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_LONG]['position']['next'], 'c8');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_LONG]['position']['current'], 'a8');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_LONG]['position']['next'], 'd8');
    }

    /**
     * @test
     */
    public function white_short()
    {
        $rule = CastlingRule::color(Symbol::WHITE);

        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['squares']['f'], 'f1');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['squares']['g'], 'g1');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['position']['current'], 'e1');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['position']['next'], 'g1');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_SHORT]['position']['current'], 'h1');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_SHORT]['position']['next'], 'f1');
    }

    /**
     * @test
     */
    public function black_short()
    {
        $rule = CastlingRule::color(Symbol::BLACK);

        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['squares']['f'], 'f8');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['squares']['g'], 'g8');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['position']['current'], 'e8');
        $this->assertSame($rule[Symbol::KING][Symbol::CASTLING_SHORT]['position']['next'], 'g8');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_SHORT]['position']['current'], 'h8');
        $this->assertSame($rule[Symbol::ROOK][Symbol::CASTLING_SHORT]['position']['next'], 'f8');
    }

    /**
     * @test
     */
    public function open_ruy_lopez()
    {
        $board = (new OpenRuyLopez(new Board()))->play();

        $expected = [
            'w' => [
                'castled' => true,
                'O-O' => false,
                'O-O-O' => false,
            ],
            'b' => [
                'castled' => false,
                'O-O' => true,
                'O-O-O' => true,
            ],
        ];

        $this->assertSame($expected, $board->getCastling());
    }
}
