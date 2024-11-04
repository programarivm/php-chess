<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\FarAdvancedPawnEval;
use Chess\Tests\AbstractUnitTestCase;

class FarAdvancedPawnEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function b6()
    {
        $expectedResult = [
            'w' => ['b6'],
            'b' => [],
        ];

        $expectedExplanation = [
            "White has a slight far advanced pawn advantage.",
        ];

        $expectedElaboration = [
            "These pawns are threatening to promote: b6.",
        ];

        $board = FenToBoardFactory::create('8/1p6/1P1K4/pk6/8/8/5B2/8 b - - 3 56');

        $farAdvancedPawnEval = new FarAdvancedPawnEval($board);

        $this->assertSame($expectedResult, $farAdvancedPawnEval->getResult());
        $this->assertSame($expectedExplanation, $farAdvancedPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $farAdvancedPawnEval->getElaboration());
    }

    /**
     * @test
     */
    public function e6_c3_e2()
    {
        $expectedResult = [
            'w' => ['e6'],
            'b' => ['c3', 'e2'],
        ];

        $expectedExplanation = [
            "Black has a slight far advanced pawn advantage.",
        ];

        $expectedElaboration = [
            "These pawns are threatening to promote: e6, c3, e2.",
        ];

        $board = FenToBoardFactory::create('8/8/4P3/4K3/8/2p2k2/4p3/8 w - - 0 1');

        $farAdvancedPawnEval = new FarAdvancedPawnEval($board);

        $this->assertSame($expectedResult, $farAdvancedPawnEval->getResult());
        $this->assertSame($expectedExplanation, $farAdvancedPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $farAdvancedPawnEval->getElaboration());
    }
}
