<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\BackwardPawnEval;
use Chess\Eval\IsolatedPawnEval;
use Chess\Tests\AbstractUnitTestCase;

class BackwardPawnEvalTest extends AbstractUnitTestCase
{
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
            "White has more backward pawns.",
        ];

        $expectedElaboration = [
            "These pawns are bakward: e4, b3.",
        ];

        $board = FenToBoardFactory::create('8/4p3/p2p4/2pP4/2P1P3/1P4k1/1P1K4/8 w - -');

        $isolatedPawnEval = new IsolatedPawnEval($board);
        $backwardPawnEval = new BackwardPawnEval($board, $isolatedPawnEval);

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
            "White has more backward pawns.",
        ];

        $expectedElaboration = [
            "These pawns are bakward: d4, e4.",
        ];

        $board = FenToBoardFactory::create('8/4p3/p2p4/2pP4/2PPP3/6k1/1P1K/8 w - -');

        $isolatedPawnEval = new IsolatedPawnEval($board);
        $backwardPawnEval = new BackwardPawnEval($board, $isolatedPawnEval);

        $this->assertSame($expectedResult, $backwardPawnEval->getResult());
        $this->assertSame($expectedExplanation, $backwardPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $backwardPawnEval->getElaboration());
    }
}
