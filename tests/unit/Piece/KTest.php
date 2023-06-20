<?php

namespace Chess\Tests\Unit\Piece;

use Chess\Piece\K;
use Chess\Play\SAN;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Rule\CastlingRule;

class KTest extends AbstractUnitTestCase
{
    static private $castlingRule;

    static private $size;

    public static function setUpBeforeClass(): void
    {
        self::$castlingRule = (new CastlingRule())->getRule();

        self::$size = [
            'files' => 8,
            'ranks' => 8,
        ];
    }

    /**
     * @test
     */
    public function w_CASTLE_LONG()
    {
        $rule = self::$castlingRule[Color::W];

        $this->assertSame($rule[Piece::K][Castle::LONG]['free'], [ 'b1', 'c1', 'd1' ]);
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
        $rule = self::$castlingRule[Color::B];

        $this->assertSame($rule[Piece::K][Castle::LONG]['free'], [ 'b8', 'c8', 'd8' ]);
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
        $rule = self::$castlingRule[Color::W];

        $this->assertSame($rule[Piece::K][Castle::SHORT]['free'], [ 'f1', 'g1' ]);
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
        $rule = self::$castlingRule[Color::B];

        $this->assertSame($rule[Piece::K][Castle::SHORT]['free'], [ 'f8', 'g8' ]);
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sq']['current'], 'e8');
        $this->assertSame($rule[Piece::K][Castle::SHORT]['sq']['next'], 'g8');
        $this->assertSame($rule[Piece::R][Castle::SHORT]['sq']['current'], 'h8');
        $this->assertSame($rule[Piece::R][Castle::SHORT]['sq']['next'], 'f8');
    }

    /**
     * @test
     */
    public function mobility_w_a2()
    {
        $king = new K('w', 'a2', self::$size);
        $mobility = (object) [
            'up' => 'a3',
            'down' => 'a1',
            'right' => 'b2',
            'upRight' => 'b3',
            'downRight' => 'b1'
        ];
        $this->assertEquals($mobility, $king->getMobility());
    }

    /**
     * @test
     */
    public function mobility_w_d5()
    {
        $king = new K('w', 'd5', self::$size);
        $mobility = (object) [
            'up' => 'd6',
            'down' => 'd4',
            'left' => 'c5',
            'right' => 'e5',
            'upLeft' => 'c6',
            'upRight' => 'e6',
            'downLeft' => 'c4',
            'downRight' => 'e4'
        ];
        $this->assertEquals($mobility, $king->getMobility());
    }

    /**
     * @test
     */
    public function mobility_w_f1()
    {
        $king = new K('w', 'f1', self::$size);

        $mobility = (object) [
            'up' => 'f2',
            'left' => 'e1',
            'right' => 'g1',
            'upLeft' => 'e2',
            'upRight' => 'g2',
        ];

        $this->assertEquals($mobility, $king->getMobility());
    }

    /**
     * @test
     */
    public function mobility_b_f8()
    {
        $king = new K('b', 'f8', self::$size);

        $mobility = (object) [
            'down' => 'f7',
            'left' => 'e8',
            'right' => 'g8',
            'downLeft' => 'e7',
            'downRight' => 'g7',
        ];

        $this->assertEquals($mobility, $king->getMobility());
    }

    /**
     * @test
     */
    public function get_sqs_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SAN($A59))->play()->getBoard();

        $king = $board->getPieceBySq('f1');

        $expected = [ 'e1', 'e2', 'g2' ];

        $this->assertSame($expected, $king->sqs());
    }

    /**
     * @test
     */
    public function mobility_w_f1_size_10_10()
    {
        $king = new K('w', 'f1', [
            'files' => 10,
            'ranks' => 10,
        ]);

        $mobility = (object) [
            'up' => 'f2',
            'left' => 'e1',
            'right' => 'g1',
            'upLeft' => 'e2',
            'upRight' => 'g2',
        ];

        $this->assertEquals($mobility, $king->getMobility());
    }
}
