<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\BackwardPawnEval;
use Chess\Piece\AsciiArray;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

class BackwardPawnEvalTest extends AbstractUnitTestCase
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
            'w' => ['e4', 'b3'],
            'b' => [],
        ];

        $expectedExplanation = [
            "Black has a moderate backward pawn advantage.",
        ];

        $expectedElaboration = [
            "e4 and b3 are backward pawns.",
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

        $backwardPawnEval = new BackwardPawnEval($board);

        $this->assertSame($expectedResult, $backwardPawnEval->getResult());
        $this->assertSame($expectedExplanation, $backwardPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $backwardPawnEval->getElaboration());
    }

    /**
     * @test
     */
    public function kaufman_16_recognizes_defended_pawns(): void
    {
        $expectedResult = [
            'w' => ['d4', 'e4'],
            'b' => [],
        ];

        $expectedExplanation = [
            "Black has a moderate backward pawn advantage.",
        ];

        $expectedElaboration = [
            "d4 and e4 are backward pawns.",
        ];

        $position = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            5 => [ ' p ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' P ', ' P ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' k ', ' . ' ],
            1 => [ ' . ', ' P ', ' . ', ' K ', ' . ', ' . ', ' . ', ' . ' ],
            0 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position, self::$square, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $backwardPawnEval = new BackwardPawnEval($board);

        $this->assertSame($expectedResult, $backwardPawnEval->getResult());
        $this->assertSame($expectedExplanation, $backwardPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $backwardPawnEval->getElaboration());
    }
}
