<?php

namespace Chess\Tests\unit\Eval;

use Chess\Eval\OverloadingEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

class OverloadingEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c4_knight()
    {
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('3b3k/8/1R1B2r1/8/2N5/8/8/K7 w - -');

        $overloadingEval = new OverloadingEval($board);

        $this->assertSame($expectedResult, $overloadingEval->result);
        $this->assertSame($expectedExplanation, $overloadingEval->explain());
        $this->assertSame($expectedElaboration, $overloadingEval->elaborate());
    }

    /**
     * @test
     */
    public function d2_queen()
    {
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('r5k1/1pp3bp/2np2p1/p4qN1/2P2Pn1/1PN1R1P1/P2Q2KP/5R2 w - -');

        $overloadingEval = new OverloadingEval($board);

        $this->assertSame($expectedResult, $overloadingEval->result);
        $this->assertSame($expectedExplanation, $overloadingEval->explain());
        $this->assertSame($expectedElaboration, $overloadingEval->elaborate());
    }

    /**
     * @test
     */
    public function overloaded_f7_pawn()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['f7'],
        ];

        $expectedExplanation = [
            "White has a slight overloading advantage.",
        ];

        $expectedElaboration = [
            "The pawn on f7 is overloaded with defensive tasks.",
        ];

        $board = FenToBoardFactory::create('6k1/5pp1/4b1n1/8/8/3BR3/5PPP/6K1 w - -');

        $overloadingEval = new OverloadingEval($board);

        $this->assertSame($expectedResult, $overloadingEval->result);
        $this->assertSame($expectedExplanation, $overloadingEval->explain());
        $this->assertSame($expectedElaboration, $overloadingEval->elaborate());
    }

    /**
     * @test
     */
    public function overloaded_g7_rook()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['g7'],
        ];

        $expectedExplanation = [
            "White has a slight overloading advantage.",
        ];

        $expectedElaboration = [
            "The rook on g7 is overloaded with defensive tasks.",
        ];

        $board = FenToBoardFactory::create('6k1/r5r1/1p3pbp/2p5/7P/2P2P1B/1P6/R5RK w - -');

        $overloadingEval = new OverloadingEval($board);

        $this->assertSame($expectedResult, $overloadingEval->result);
        $this->assertSame($expectedExplanation, $overloadingEval->explain());
        $this->assertSame($expectedElaboration, $overloadingEval->elaborate());
    }

    /**
     * @test
     */
    public function overloaded_c4_knight_defending_pawn_and_bishop()
    {
        $expectedResult = [
            'w' => ['c4'],
            'b' => [],
        ];

        $expectedExplanation = [
            "Black has a slight overloading advantage.",
        ];

        $expectedElaboration = [
            "The knight on c4 is overloaded with defensive tasks.",
        ];

        $board = FenToBoardFactory::create('3b3k/8/1P1B2r1/8/2N5/8/8/K7 w - -');

        $overloadingEval = new OverloadingEval($board);

        $this->assertSame($expectedResult, $overloadingEval->result);
        $this->assertSame($expectedExplanation, $overloadingEval->explain());
        $this->assertSame($expectedElaboration, $overloadingEval->elaborate());
    }

    /**
     * @test
     */
    public function overloaded_c4_knight_defending_rook_and_bishop()
    {
        $expectedResult = [
            'w' => ['c4'],
            'b' => [],
        ];

        $expectedExplanation = [
            "Black has a slight overloading advantage.",
        ];

        $expectedElaboration = [
            "The knight on c4 is overloaded with defensive tasks.",
        ];

        $board = FenToBoardFactory::create('3b3k/8/1R1B2r1/8/2N5/3r4/8/K7 w - -');

        $overloadingEval = new OverloadingEval($board);

        $this->assertSame($expectedResult, $overloadingEval->result);
        $this->assertSame($expectedExplanation, $overloadingEval->explain());
        $this->assertSame($expectedElaboration, $overloadingEval->elaborate());
    }
}
