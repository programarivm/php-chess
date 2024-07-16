<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\DoubledPawnEval;
use Chess\Tests\AbstractUnitTestCase;

class DoubledPawnEvalTest extends AbstractUnitTestCase
{
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

        $board = FenToBoardFactory::create('8/4p3/p2p4/2pP4/2P1P3/1P4k1/1P1K4/8 w - -');

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

        $board = FenToBoardFactory::create('1r1q1rk1/p1p2pbp/2pp1np1/6B1/4P3/2NQ4/PPP2PPP/3R1RK1 w - -');
        
        $doubledPawnEval = new DoubledPawnEval($board);

        $this->assertSame($expectedResult, $doubledPawnEval->getResult());
        $this->assertSame($expectedExplanation, $doubledPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $doubledPawnEval->getElaboration());
    }
}
