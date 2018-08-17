<?php

namespace PGNChess\Tests\Unit\Square;

use PGNChess\Square\Castling;
use PGNChess\PGN\Symbol;
use PHPUnit\Framework\TestCase;

class CastlingTest extends TestCase
{
    /**
     * @test
     */
    public function white_long_castling_info()
    {
        $castlingInfo = Castling::info(Symbol::WHITE);

        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->b, 'b1');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->c, 'c1');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->d, 'd1');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->position->current, 'e1');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->position->next, 'c1');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_LONG}->position->current, 'a1');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_LONG}->position->next, 'd1');
    }

    /**
     * @test
     */
    public function black_long_castling_info()
    {
        $castlingInfo = Castling::info(Symbol::BLACK);

        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->b, 'b8');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->c, 'c8');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->d, 'd8');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->position->current, 'e8');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_LONG}->position->next, 'c8');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_LONG}->position->current, 'a8');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_LONG}->position->next, 'd8');
    }

    /**
     * @test
     */
    public function white_short_castling_info()
    {
        $castlingInfo = Castling::info(Symbol::WHITE);

        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->f, 'f1');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->g, 'g1');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position->current, 'e1');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position->next, 'g1');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_SHORT}->position->current, 'h1');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_SHORT}->position->next, 'f1');
    }

    /**
     * @test
     */
    public function black_short_castling_info()
    {
        $castlingInfo = Castling::info(Symbol::BLACK);

        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->f, 'f8');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->g, 'g8');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position->current, 'e8');
        $this->assertEquals($castlingInfo->{Symbol::KING}->{Symbol::CASTLING_SHORT}->position->next, 'g8');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_SHORT}->position->current, 'h8');
        $this->assertEquals($castlingInfo->{Symbol::ROOK}->{Symbol::CASTLING_SHORT}->position->next, 'f8');
    }
}
