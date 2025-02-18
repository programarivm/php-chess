<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\BackwardPawnEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

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
            "White has more backward pawns against.",
        ];

        $expectedElaboration = [
            "These pawns are bakward: e4, b3.",
        ];

        $board = FenToBoardFactory::create('8/4p3/p2p4/2pP4/2P1P3/1P4k1/1P1K4/8 w - -');

        $backwardPawnEval = new BackwardPawnEval($board);

        $this->assertSame($expectedResult, $backwardPawnEval->result);
        $this->assertSame($expectedExplanation, $backwardPawnEval->explain());
        $this->assertSame($expectedElaboration, $backwardPawnEval->elaborate());
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
            "White has more backward pawns against.",
        ];

        $expectedElaboration = [
            "These pawns are bakward: d4, e4.",
        ];

        $board = FenToBoardFactory::create('8/4p3/p2p4/2pP4/2PPP3/6k1/1P1K4/8 w - -');

        $backwardPawnEval = new BackwardPawnEval($board);

        $this->assertSame($expectedResult, $backwardPawnEval->result);
        $this->assertSame($expectedExplanation, $backwardPawnEval->explain());
        $this->assertSame($expectedElaboration, $backwardPawnEval->elaborate());
    }
}
