<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\IsolatedPawnEval;
use Chess\Piece\AsciiArray;
use Chess\Tests\AbstractUnitTestCase;
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
            'w' => 0,
            'b' => 2,
        ];

        $expectedPhrase = [
            "The pawns on a7 and d5 are isolated.",
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
            'w' => 1,
            'b' => 1,
        ];

        $expectedPhrase = [
            "The pawns on d5 and h2 are isolated.",
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
            'w' => 2,
            'b' => 1,
        ];

        $expectedPhrase = [
            "The pawns on a7, a2 and c2 are isolated.",
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
}
