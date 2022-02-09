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
    public function pawn_forks_bishop_and_knight()
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
    public function pawn_forks_king_and_knight()
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

    /**
     * @test
     */
    public function pawn_forks_king_and_rook()
    {
        $board = (new StringToBoard('8/8/5k1r/6P1/7K/8/8/8 w - -'))
            ->create();

        $expected = [
            'w' => 5.1,
            'b' => 0,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }

    /**
     * @test
     */
    public function pawn_forks_king_and_queen()
    {
        $board = (new StringToBoard('8/8/5k1q/6P1/7K/8/8/8 w - -'))
            ->create();

        $expected = [
            'w' => 8.8,
            'b' => 0,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }

    /**
     * @test
     */
    public function knight_forks_king_and_bishop()
    {
        $board = (new StringToBoard('8/8/1k6/4b1P1/2N4K/8/8/8 w - -'))
            ->create();

        $expected = [
            'w' => 3.33,
            'b' => 0,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }

    /**
     * @test
     */
    public function knight_forks_king_and_knight()
    {
        $board = (new StringToBoard('8/8/1k6/4n1P1/2N4K/8/8/8 w - -'))
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
    public function bishop_forks_king_and_rook()
    {
        $board = (new StringToBoard('8/8/2k5/5b2/6R1/8/2K5/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 5.1,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }

    /**
     * @test
     */
    public function bishop_forks_king_and_queen()
    {
        $board = (new StringToBoard('8/8/2k5/5b2/6Q1/8/2K5/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 8.8,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }

    /**
     * @test
     */
    public function bishop_forks_king_and_knight()
    {
        $board = (new StringToBoard('8/8/2k5/5b2/6N1/8/2K5/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absForkEvald = (new AbsoluteForkEvaluation($board))->evaluate();

        $this->assertSame($expected, $absForkEvald);
    }
}
