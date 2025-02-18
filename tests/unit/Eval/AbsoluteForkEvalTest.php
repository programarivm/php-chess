<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AbsoluteForkEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class AbsoluteForkEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function pawn_forks_bishop_and_knight()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('8/1k6/5b1n/6P1/7K/8/8/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function pawn_forks_king_and_knight()
    {
        $expectedResult = [
            'w' => 3.2,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has an absolute fork advantage.",
        ];

        $expectedElaboration = [
            "The pawn on g5 is attacking both the knight on h6 and the opponent's king at the same time.",
        ];

        $board = FenToBoardFactory::create('8/8/5k1n/6P1/7K/8/8/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function pawn_forks_king_and_rook()
    {
        $expectedResult = [
            'w' => 5.1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has an absolute fork advantage.",
        ];

        $expectedElaboration = [
            "The pawn on g5 is attacking both the rook on h6 and the opponent's king at the same time.",
        ];

        $board = FenToBoardFactory::create('8/8/5k1r/6P1/7K/8/8/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function pawn_forks_king_and_queen()
    {
        $expectedResult = [
            'w' => 8.8,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has an absolute fork advantage.",
        ];

        $expectedElaboration = [
            "The pawn on g5 is attacking both Black's queen on h6 and the opponent's king at the same time.",
        ];

        $board = FenToBoardFactory::create('8/8/5k1q/6P1/7K/8/8/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function knight_forks_king_and_bishop()
    {
        $expectedResult = [
            'w' => 3.33,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has an absolute fork advantage.",
        ];

        $expectedElaboration = [
            "The knight on c4 is attacking both the bishop on e5 and the opponent's king at the same time.",
        ];

        $board = FenToBoardFactory::create('8/8/1k6/4b1P1/2N4K/8/8/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function knight_forks_king_and_knight()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];
    
        $board = FenToBoardFactory::create('8/8/1k6/4n1P1/2N4K/8/8/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function bishop_forks_king_and_rook()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 5.1,
        ];

        $expectedExplanation = [
            "Black has an absolute fork advantage.",
        ];

        $expectedElaboration = [
            "The bishop on f5 is attacking both the rook on g4 and the opponent's king at the same time.",
        ];

        $board = FenToBoardFactory::create('8/8/2k5/5b2/6R1/8/2K5/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function bishop_forks_king_and_queen()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 8.8,
        ];

        $expectedExplanation = [
            "Black has an absolute fork advantage.",
        ];

        $expectedElaboration = [
            "The bishop on f5 is attacking both White's queen on g4 and the opponent's king at the same time.",
        ];

        $board = FenToBoardFactory::create('8/8/2k5/5b2/6Q1/8/2K5/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function bishop_forks_king_and_knight()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('8/8/2k5/5b2/6N1/8/2K5/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function knight_forks_rook_and_rook()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('8/2k5/3r4/8/2N5/5K2/1r6/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }

    /**
     * @test
     */
    public function knight_forks_queen_and_rook()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('8/5R2/2kn4/8/2Q5/8/6K1/8 w - -');

        $absoluteForkEval = new AbsoluteForkEval($board);

        $this->assertSame($expectedResult, $absoluteForkEval->result);
        $this->assertSame($expectedExplanation, $absoluteForkEval->explain());
        $this->assertSame($expectedElaboration, $absoluteForkEval->elaborate());
    }
}
