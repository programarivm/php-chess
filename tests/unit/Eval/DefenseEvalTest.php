<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DefenseEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

class DefenseEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense_center_gambit_Bg4()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = FenToBoardFactory::create('r2qkbnr/ppp2ppp/2np4/1B2p3/3PP1b1/5N2/PPP2PPP/RNBQK2R w KQkq -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }

    /**
     * @test
     */
    public function attacking_rook_with_a_knight_shielding_the_queen()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = FenToBoardFactory::create('4qk2/8/4n3/8/8/4R3/8/6K1 w - -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }

    /**
     * @test
     */
    public function attacking_rook_with_a_knight_shielding_the_unprotected_queen()
    {
        $expectedResult = [
            'w' => 8.8,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a moderate defense advantage."
        ];

        $expectedElaboration = [
            "If the knight on e6 moved, these pieces may well be exposed to attack: The rook on e3, Black's queen on e8.",
        ];

        $board = FenToBoardFactory::create('4q1k1/8/4n3/8/8/4R3/8/6K1 w - -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }

    /**
     * @test
     */
    public function attacking_bishop_with_a_knight_shielding_the_queen()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = FenToBoardFactory::create('4qk2/8/2n5/8/B7/8/8/6K1 w - -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }

    /**
     * @test
     */
    public function attacking_bishop_with_a_knight_shielding_the_unprotected_queen()
    {
        $expectedResult = [
            'w' => 8.8,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a moderate defense advantage."
        ];

        $expectedElaboration = [
            "If the knight on c6 moved, these pieces may well be exposed to attack: The bishop on a4, Black's queen on e8.",
        ];

        $board = FenToBoardFactory::create('4q1k1/8/2n5/8/B7/8/8/6K1 w - -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }

    /**
     * @test
     */
    public function attacking_bishop_with_a_knight_shielding_the_unprotected_rook()
    {
        $expectedResult = [
            'w' => 1.9,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a slight defense advantage."
        ];

        $expectedElaboration = [
            "If the knight on c6 moved, the rook on e8 may well be exposed to attack.",
        ];

        $board = FenToBoardFactory::create('4r1k1/8/2n5/8/B7/8/8/6K1 w - -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }

    /**
     * @test
     */
    public function c60()
    {
        $expectedResult = [
            'w' => 1.0,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a slight defense advantage."
        ];

        $expectedElaboration = [
            "If the knight on c6 moved, the pawn on e5 may well be exposed to attack.",
        ];

        $board = FenToBoardFactory::create('r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }

    /**
     * @test
     */
    public function a13()
    {
        $expectedResult = [
            'w' => 5.1,
            'b' => 9.8,
        ];

        $expectedExplanation = [
            "Black has a slight defense advantage."
        ];

        $expectedElaboration = [
            "If the pawn on a7 moved, these pieces may well be exposed to attack: White's queen on a4, the rook on a8.",
            "If the knight on b5 moved, these pieces may well be exposed to attack: White's queen on a4, the pawn on b2.",
        ];

        $board = FenToBoardFactory::create('rn2k1nr/pp1b1ppp/1q6/1N1p4/Q1pP4/4P3/PP1K1PPP/R4BNR w kq -');
        $defenseEval = new DefenseEval($board);

        $this->assertSame($expectedResult, $defenseEval->result);
        $this->assertSame($expectedExplanation, $defenseEval->explain());
        $this->assertSame($expectedElaboration, $defenseEval->elaborate());
    }
}
