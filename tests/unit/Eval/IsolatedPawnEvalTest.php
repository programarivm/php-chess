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
    static private $size;

    static private $castlingRule;

    public static function setUpBeforeClass(): void
    {
        self::$size = Square::SIZE;

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

        $expectedPhrase = [
            "White has some isolated pawn advantage.",
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

        $board = (new AsciiArray($position, self::$size, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $isolatedPawnEval->getPhrases());
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

        $expectedPhrase = [];

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

        $board = (new AsciiArray($position, self::$size, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $isolatedPawnEval->getPhrases());
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

        $expectedPhrase = [
            "Black has a tiny isolated pawn advantage.",
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

        $board = (new AsciiArray($position, self::$size, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $isolatedPawnEval->getPhrases());
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
