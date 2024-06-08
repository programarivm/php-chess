<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\IsolatedPawnEval;
use Chess\Piece\AsciiArray;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

class IsolatedPawnEvalTest extends AbstractUnitTestCase
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
    public function kaufman_09()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['a7', 'd5'],
        ];

        $expectedExplanation = [
            "White has a moderate isolated pawn advantage.",
        ];

        $expectedElaboration = [
            "a7 and d5 are isolated pawns.",
        ];

        $position = [
            7 => [ ' r ', ' . ', ' . ', ' . ', ' k ', ' . ', ' . ', ' r ' ],
            6 => [ ' p ', ' b ', ' n ', ' . ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' P ', ' . ', ' p ', ' P ', ' . ', ' . ', ' . ' ],
            3 => [ ' P ', ' . ', ' q ', ' P ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' B ', ' . ', ' . ' ],
            1 => [ ' . ', ' . ', ' . ', ' Q ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' R ', ' . ', ' . ', ' . ', ' K ', ' . ', ' . ', ' R ' ],
        ];

        $board = (new AsciiArray($position, self::$square, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->getResult());
        $this->assertSame($expectedExplanation, $isolatedPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $isolatedPawnEval->getElaboration());
    }

    /**
     * @test
     */
    public function kaufman_13()
    {
        $expectedResult = [
            'w' => ['h2'],
            'b' => ['d5'],
        ];

        $expectedExplanation = [];

        $expectedElaboration = [
            "h2 and d5 are isolated pawns.",
        ];

        $position = [
            7 => [ ' . ', ' r ', ' . ', ' . ', ' . ', ' . ', ' k ', ' . ' ],
            6 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' n ', ' p ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' n ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' N ', ' B ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' P ' ],
            0 => [ ' . ', ' . ', ' . ', ' N ', ' . ', ' R ', ' K ', ' . ' ],
        ];

        $board = (new AsciiArray($position, self::$square, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->getResult());
        $this->assertSame($expectedExplanation, $isolatedPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $isolatedPawnEval->getElaboration());
    }

    /**
     * @test
     */
    public function kaufman_14()
    {
        $expectedResult = [
            'w' => ['a2', 'c2'],
            'b' => ['a7'],
        ];

        $expectedExplanation = [
            "Black has a slight isolated pawn advantage.",
        ];

        $expectedElaboration = [
            "a2, c2 and a7 are isolated pawns.",
        ];

        $position = [
            7 => [ ' . ', ' r ', ' . ', ' . ', ' r ', ' . ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ', ' B ' ],
            4 => [ ' q ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' Q ', ' . ', ' P ', ' . ' ],
            1 => [ ' P ', ' b ', ' P ', ' . ', ' . ', ' P ', ' K ', ' P ' ],
            0 => [ ' . ', ' R ', ' . ', ' . ', ' . ', ' R ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position, self::$square, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->getResult());
        $this->assertSame($expectedExplanation, $isolatedPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $isolatedPawnEval->getElaboration());
    }

    /**
     * @test
     */
    public function capablanca_f4()
    {
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];

        $board = FenToBoardFactory::create(
            'rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3',
            new CapablancaBoard()
        );

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->getResult());
    }
}
