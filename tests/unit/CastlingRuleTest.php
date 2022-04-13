<?php

namespace Chess\Tests\Unit;

use Chess\CastlingRule;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Tests\AbstractUnitTestCase;

class CastlingRuleTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function w_CASTLE_LONG()
    {
        $rule = CastlingRule::color(Color::W);

        $this->assertSame($rule[Piece::K][Castle::LONG]['sqs']['b'], 'b1');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sqs']['c'], 'c1');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sqs']['d'], 'd1');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sq']['current'], 'e1');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sq']['next'], 'c1');
        $this->assertSame($rule[Piece::R][Castle::LONG]['sq']['current'], 'a1');
        $this->assertSame($rule[Piece::R][Castle::LONG]['sq']['next'], 'd1');
    }

    /**
     * @test
     */
    public function b_CASTLE_LONG()
    {
        $rule = CastlingRule::color(Color::B);

        $this->assertSame($rule[Piece::K][Castle::LONG]['sqs']['b'], 'b8');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sqs']['c'], 'c8');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sqs']['d'], 'd8');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sq']['current'], 'e8');
        $this->assertSame($rule[Piece::K][Castle::LONG]['sq']['next'], 'c8');
        $this->assertSame($rule[Piece::R][Castle::LONG]['sq']['current'], 'a8');
        $this->assertSame($rule[Piece::R][Castle::LONG]['sq']['next'], 'd8');
    }

    /**
     * @test
     */
    public function w_CASTLE_SHORT()
    {
        $rule = CastlingRule::color(Color::W);

        $this->assertSame($rule[Piece::K][Castle::SHORT]['sqs']['f'], 'f1');
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sqs']['g'], 'g1');
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sq']['current'], 'e1');
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sq']['next'], 'g1');
        $this->assertSame($rule[Piece::R][Castle::SHORT]['sq']['current'], 'h1');
        $this->assertSame($rule[Piece::R][Castle::SHORT]['sq']['next'], 'f1');
    }

    /**
     * @test
     */
    public function b_CASTLE_SHORT()
    {
        $rule = CastlingRule::color(Color::B);

        $this->assertSame($rule[Piece::K][Castle::SHORT]['sqs']['f'], 'f8');
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sqs']['g'], 'g8');
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sq']['current'], 'e8');
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sq']['next'], 'g8');
        $this->assertSame($rule[Piece::R][Castle::SHORT]['sq']['current'], 'h8');
        $this->assertSame($rule[Piece::R][Castle::SHORT]['sq']['next'], 'f8');
    }
}
