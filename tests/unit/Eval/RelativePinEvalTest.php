<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\RelativePinEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class RelativePinEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $board = (new StrToBoard('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'))
            ->create();

        $result = (new RelativePinEval($board))->result;

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense_center_gambit_Bg4()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 5.47,
        ];

        $expectedExplanation = [
            "Black has a relative pin advantage.",
        ];

        $expectedElaboration = [
            "The knight on f3 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('r2qkbnr/ppp2ppp/2np4/1B2p3/3PP1b1/5N2/PPP2PPP/RNBQK2R w KQkq -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }

    /**
     * @test
     */
    public function pinning_rook_pinned_knight_shielded_queen()
    {
        $expectedResult = [
            'w' => 3.7,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a relative pin advantage.",
        ];

        $expectedElaboration = [
            "The knight on e6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4q1k1/8/4n3/8/8/4R3/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }

    /**
     * @test
     */
    public function pinning_bishop_pinned_knight_shielded_queen()
    {
        $expectedResult = [
            'w' => 5.47,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a relative pin advantage.",
        ];

        $expectedElaboration = [
            "The knight on c6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4q1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }

    /**
     * @test
     */
    public function pinning_bishop_pinned_knight_shielded_rook()
    {
        $expectedResult = [
            'w' => 1.77,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a relative pin advantage.",
        ];

        $expectedElaboration = [
            "The knight on c6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4r1k1/8/2n5/8/B7/8/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }

    /**
     * @test
     */
    public function pinning_bishop_pinned_knight_shielded_rook_and_attacked_rook()
    {
        $expectedResult = [
            'w' => 1.77,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a relative pin advantage.",
        ];

        $expectedElaboration = [
            "The knight on c6 is pinned shielding a piece that is more valuable than the attacking piece.",
        ];

        $board = (new StrToBoard('4r1k1/8/2n5/8/B2R4/8/8/6K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }

    /**
     * @test
     */
    public function e6_pawn()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = (new StrToBoard('4r1k1/4r3/4p3/8/8/8/8/4R1K1 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }

    /**
     * @test
     */
    public function b5_knight()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = (new StrToBoard('rrb3k1/3qn1bp/3p4/1NpP4/P7/1PN1Bp1P/R2Q1P1K/1R6 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }

    /**
     * @test
     */
    public function f5_pawn()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = (new StrToBoard('8/7p/1pnnk3/3N1p2/2K2P2/P2B3P/8/8 w - -'))
            ->create();

        $relativePinEval = new RelativePinEval($board);

        $this->assertSame($expectedResult, $relativePinEval->result);
        $this->assertSame($expectedExplanation, $relativePinEval->explain());
        $this->assertSame($expectedElaboration, $relativePinEval->elaborate());
    }
}
