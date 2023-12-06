<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\PassedPawnEval;
use Chess\Piece\AsciiArray;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

class PassedPawnEvalTest extends AbstractUnitTestCase
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
    public function kaufman_13()
    {
        $expectedEval = [
            'w' => 0,
            'b' => 4,
        ];

        $expectedPhrase = [
            "The pawn on d5 is passed.",
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

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedEval, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getPhrases());
    }

    /**
     * @test
     */
    public function kaufman_14()
    {
        $expectedEval = [
            'w' => 2,
            'b' => 0,
        ];

        $expectedPhrase = [
            "The pawn on c2 is passed.",
        ];

        $position = [
            7 => [ ' . ', ' r ', ' . ', ' . ', ' r ', ' . ', ' k ', ' . ' ],
            6 => [ ' p ', ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' p ', ' B ' ],
            4 => [ ' q ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' Q ', ' . ', ' . ', ' P ', ' . ' ],
            1 => [ ' P ', ' b ', ' P ', ' . ', ' . ', ' P ', ' K ', ' P ' ],
            0 => [ ' . ', ' R ', ' . ', ' . ', ' . ', ' R ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position, self::$size, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedEval, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getPhrases());
    }

    /**
     * @test
     */
    public function kaufman_21()
    {
        $expectedEval = [
            'w' => 0,
            'b' => 11,
        ];

        $expectedPhrase = [
            "The pawn on c4 is passed.",
            "The pawn on d3 is passed.",
        ];

        $position = [
            7 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            6 => [ ' . ', ' B ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' K ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' p ', ' . ', ' . ', ' b ', ' n ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' k ', ' . ', ' . ' ],
            0 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
        ];

        $board = (new AsciiArray($position, self::$size, self::$castlingRule))
            ->toClassicalBoard('\Chess\Variant\Classical\Board', 'w');

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedEval, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getPhrases());
    }
}
