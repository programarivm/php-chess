<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
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
        $expectedResult = [
            'w' => [],
            'b' => ['d5'],
        ];

        $expectedPhrase = [
            "Black has a tiny passed pawn advantage.",
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

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getPhrases());
    }

    /**
     * @test
     */
    public function kaufman_14()
    {
        $expectedResult = [
            'w' => ['c2'],
            'b' => [],
        ];

        $expectedPhrase = [
            "White has a tiny passed pawn advantage.",
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

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getPhrases());
    }

    /**
     * @test
     */
    public function kaufman_21()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['e6', 'f5'],
        ];

        $expectedPhrase = [
            "Black has some passed pawn advantage.",
        ];

        $board = FenToBoardFactory::create('8/2k5/4p3/1nb2p2/2K5/8/6B1/8 w - -');

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getPhrases());
    }

    /**
     * @test
     */
    public function a4()
    {
        $expectedResult = [
            'w' => ['a4'],
            'b' => [],
        ];

        $expectedPhrase = [
            "White has a tiny passed pawn advantage.",
        ];

        $board = FenToBoardFactory::create('8/8/8/5k2/P7/4K3/8/8 w - - 0 1');

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getPhrases());
    }
}
