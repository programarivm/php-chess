<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\AbsoluteForkEvaluation;
use Chess\FEN\StringToBoard;
use Chess\Tests\AbstractUnitTestCase;

class AbsoluteForkEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $board = (new StringToBoard('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }

    /**
     * @test
     */
    public function pawn_attacks_bishop_and_knight()
    {
        $board = (new StringToBoard('8/1k6/5b1n/6P1/7K/8/8/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }

    /**
     * @test
     */
    public function pawn_attacks_king_and_knight()
    {
        $board = (new StringToBoard('8/8/5k1n/6P1/7K/8/8/8 w - -'))
            ->create();

        $expected = [
            'w' => 3.2,
            'b' => 0,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }
}
