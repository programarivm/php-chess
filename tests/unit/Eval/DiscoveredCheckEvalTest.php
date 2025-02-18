<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DiscoveredCheckEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class DiscoveredCheckEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function b20_sicilian_defense()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has a slight discovered check advantage.",
        ];

        $expectedElaboration = [
            "The White king can be put in check as long as the pawn on e5 moves out of the way.",
        ];

        $board = FenToBoardFactory::create('r1b1kbnr/pp3ppp/2n1q3/4p3/1pP5/P4N2/1B1P1PPP/RN1QKB1R w KQkq -');
        $discoveredCheckEval = new DiscoveredCheckEval($board);

        $this->assertSame($expectedResult, $discoveredCheckEval->result);
        $this->assertSame($expectedExplanation, $discoveredCheckEval->explain());
        $this->assertSame($expectedElaboration, $discoveredCheckEval->elaborate());
    }

    /**
     * @test
     */
    public function knight()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedExplanation = [
            "Black has a slight discovered check advantage.",
        ];

        $expectedElaboration = [
            "The White king can be put in check as long as the knight on c7 moves out of the way.",
        ];

        $board = FenToBoardFactory::create('2r5/2n5/5k2/8/8/2K5/8/8 w - - 0 1');
        $discoveredCheckEval = new DiscoveredCheckEval($board);

        $this->assertSame($expectedResult, $discoveredCheckEval->result);
        $this->assertSame($expectedExplanation, $discoveredCheckEval->explain());
        $this->assertSame($expectedElaboration, $discoveredCheckEval->elaborate());
    }

    /**
     * @test
     */
    public function knight_and_rook()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 8.3,
        ];

        $expectedExplanation = [
            "Black has a moderate discovered check advantage.",
        ];

        $expectedElaboration = [
            "The White king can be put in check as long as the knight on c7 moves out of the way.",
            "The White king can be put in check as long as the rook on f6 moves out of the way.",
        ];

        $board = FenToBoardFactory::create('2r4k/2n3b1/5r2/8/8/2K5/8/8 w - - 0 1');
        $discoveredCheckEval = new DiscoveredCheckEval($board);

        $this->assertSame($expectedResult, $discoveredCheckEval->result);
        $this->assertSame($expectedExplanation, $discoveredCheckEval->explain());
        $this->assertSame($expectedElaboration, $discoveredCheckEval->elaborate());
    }
}
