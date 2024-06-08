<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DoubledPawnEval;
use Chess\Piece\AsciiArray;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

class DoubledPawnEvalTest extends AbstractUnitTestCase
{
    static private $square;

    static private $castlingRule;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();

        self::$castlingRule = (new CastlingRule())->getRule();
    }

    /**
     * @test
     */
    public function kaufman_16()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "Black has a slight doubled pawn advantage.",
        ];

        $expectedElaboration = [
            "The pawn on b3 is doubled.",
        ];

        $position = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            5 => [ ' p ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' P ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' P ', ' . ', ' . ', ' . ', ' . ', ' k ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' K ', ' . ', ' . ', ' . ', ' . ' ],
            0 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position, self::$square, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $doubledPawnEval = new DoubledPawnEval($board);

        $this->assertSame($expectedResult, $doubledPawnEval->getResult());
        $this->assertSame($expectedExplanation, $doubledPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $doubledPawnEval->getElaboration());
    }

    /**
     * @test
     */
    public function kaufman_17()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "White has a slight doubled pawn advantage.",
        ];

        $expectedElaboration = [
            "The pawn on c6 is doubled.",
        ];

        $position = [
            7 => [ ' . ', ' r ', ' . ', ' q ', ' . ', ' r ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' p ', ' . ', ' . ', ' p ', ' b ', ' p ' ],
            5 => [ ' . ', ' . ', ' p ', ' p ', ' . ', ' k ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' B ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' Q ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' . ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' . ', ' . ', ' . ', ' R ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $board = (new AsciiArray($position, self::$square, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $doubledPawnEval = new DoubledPawnEval($board);

        $this->assertSame($expectedResult, $doubledPawnEval->getResult());
        $this->assertSame($expectedExplanation, $doubledPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $doubledPawnEval->getElaboration());
    }
}
