<?php

namespace Chess\Tests\Unit\Piece\Classical;

use Chess\Piece\Classical\K;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\PGN\AN\Square as CapablancaSquare;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square as ClassicalSquare;
use Chess\Variant\Classical\Rule\CastlingRule;

class KTest extends AbstractUnitTestCase
{
    static private CastlingRule $castlingRule;

    static private ClassicalSquare $square;

    public static function setUpBeforeClass(): void
    {
        self::$castlingRule = new CastlingRule();

        self::$square = new ClassicalSquare();
    }

    /**
     * @test
     */
    public function w_CASTLE_LONG()
    {
        $rule = self::$castlingRule->rule[Color::W];

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
        $rule = self::$castlingRule->rule[Color::B];

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
        $rule = self::$castlingRule->rule[Color::W];

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
        $rule = self::$castlingRule->rule[Color::B];

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
        $king = new K('w', 'a2', self::$square);
        $mobility = ['a3', 'a1', 'b2', 'b3', 'b1'];

        $this->assertEquals($mobility, $king->mobility);
    }

    /**
     * @test
     */
    public function mobility_w_d5()
    {
        $king = new K('w', 'd5', self::$square);
        $mobility = ['d6', 'd4', 'c5', 'e5', 'c6', 'e6', 'c4', 'e4'];

        $this->assertEquals($mobility, $king->mobility);
    }

    /**
     * @test
     */
    public function mobility_w_f1()
    {
        $king = new K('w', 'f1', self::$square);
        $mobility = ['f2', 'e1', 'g1', 'e2', 'g2'];

        $this->assertEquals($mobility, $king->mobility);
    }

    /**
     * @test
     */
    public function mobility_b_f8()
    {
        $king = new K('b', 'f8', self::$square);
        $mobility = ['f7', 'e8', 'g8', 'e7', 'g7'];

        $this->assertEquals($mobility, $king->mobility);
    }

    /**
     * @test
     */
    public function legal_sqs_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->board;

        $king = $board->pieceBySq('f1');

        $expected = [ 'e1', 'e2', 'g2' ];

        $this->assertEqualsCanonicalizing($expected, $king->legalSqs());
    }

    /**
     * @test
     */
    public function capablanca_mobility_w_f1()
    {
        $king = new K('w', 'f1', new CapablancaSquare());

        $mobility = ['f2', 'e1', 'g1', 'e2', 'g2'];

        $this->assertEquals($mobility, $king->mobility);
    }
}
