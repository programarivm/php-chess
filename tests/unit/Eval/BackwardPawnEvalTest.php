<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\BackwardPawnEval;
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
            "Black has a moderate backward pawn advantage.",
        ];

        $expectedElaboration = [
            "e4 and b3 are backward pawns.",
        ];

        $board = FenToBoardFactory::create('8/4p3/p2p4/2pP4/2P1P3/1P4k1/1P1K4/8 w - -');

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

        $board = FenToBoardFactory::create('8/4p3/p2p4/2pP4/2PPP3/6k1/1P1K/8 w - -');
        
        $backwardPawnEval = new BackwardPawnEval($board);

        $this->assertSame($expectedResult, $backwardPawnEval->getResult());
        $this->assertSame($expectedExplanation, $backwardPawnEval->getExplanation());
        $this->assertSame($expectedElaboration, $backwardPawnEval->getElaboration());
    }
}
