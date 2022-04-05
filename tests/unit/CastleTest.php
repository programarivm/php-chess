<?php

namespace Chess\Tests\Unit;

use Chess\Board;
use Chess\Castle;
use Chess\PGN\Symbol;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\RuyLopez\Open as OpenRuyLopez;

class CastleTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function white_long()
    {
        $rule = Castle::color(Symbol::WHITE);

        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sqs']['b'], 'b1');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sqs']['c'], 'c1');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sqs']['d'], 'd1');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sq']['current'], 'e1');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sq']['next'], 'c1');
        $this->assertSame($rule[Symbol::R][Symbol::O_O_O]['sq']['current'], 'a1');
        $this->assertSame($rule[Symbol::R][Symbol::O_O_O]['sq']['next'], 'd1');
    }

    /**
     * @test
     */
    public function black_long()
    {
        $rule = Castle::color(Symbol::BLACK);

        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sqs']['b'], 'b8');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sqs']['c'], 'c8');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sqs']['d'], 'd8');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sq']['current'], 'e8');
        $this->assertSame($rule[Symbol::K][Symbol::O_O_O]['sq']['next'], 'c8');
        $this->assertSame($rule[Symbol::R][Symbol::O_O_O]['sq']['current'], 'a8');
        $this->assertSame($rule[Symbol::R][Symbol::O_O_O]['sq']['next'], 'd8');
    }

    /**
     * @test
     */
    public function white_short()
    {
        $rule = Castle::color(Symbol::WHITE);

        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sqs']['f'], 'f1');
        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sqs']['g'], 'g1');
        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sq']['current'], 'e1');
        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sq']['next'], 'g1');
        $this->assertSame($rule[Symbol::R][Symbol::O_O]['sq']['current'], 'h1');
        $this->assertSame($rule[Symbol::R][Symbol::O_O]['sq']['next'], 'f1');
    }

    /**
     * @test
     */
    public function black_short()
    {
        $rule = Castle::color(Symbol::BLACK);

        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sqs']['f'], 'f8');
        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sqs']['g'], 'g8');
        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sq']['current'], 'e8');
        $this->assertSame($rule[Symbol::K][Symbol::O_O]['sq']['next'], 'g8');
        $this->assertSame($rule[Symbol::R][Symbol::O_O]['sq']['current'], 'h8');
        $this->assertSame($rule[Symbol::R][Symbol::O_O]['sq']['next'], 'f8');
    }

    /**
     * @test
     */
    public function open_ruy_lopez()
    {
        $board = (new OpenRuyLopez(new Board()))->play();

        $expected = [
            'w' => [
                'isCastled' => true,
                'O-O' => false,
                'O-O-O' => false,
            ],
            'b' => [
                'isCastled' => false,
                'O-O' => true,
                'O-O-O' => true,
            ],
        ];

        $this->assertSame($expected, $board->getCastle());
    }
}
