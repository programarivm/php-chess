<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\BishopPairEvaluation;
use Chess\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Opening\Sicilian\Closed as ClosedSicilian;
use Chess\Tests\Sample\Opening\RuyLopez\Exchange as RuyLopezExchange;

class BishopPairEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function closed_sicilian()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $bishopPairEval = (new BishopPairEvaluation($board))->eval();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertSame($expected, $bishopPairEval);
    }

    /**
     * @test
     */
    public function ruy_lopez_exchange()
    {
        $board = (new RuyLopezExchange(new Board()))->play();

        $bishopPairEval = (new BishopPairEvaluation($board))->eval();

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $this->assertSame($expected, $bishopPairEval);
    }

    /**
     * @test
     */
    public function B_B_vs_b_b()
    {
        $board = (new StrToBoard('8/5b2/4k3/4b3/8/8/1KBB4/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absForkEval = (new BishopPairEvaluation($board))->eval();

        $this->assertSame($expected, $absForkEval);
    }

    /**
     * @test
     */
    public function B_B_vs_n_b()
    {
        $board = (new StrToBoard('8/5n2/4k3/4b3/8/8/1KBB4/8 w - -'))
            ->create();

        $expected = [
            'w' => 1,
            'b' => 0,
        ];

        $absForkEval = (new BishopPairEvaluation($board))->eval();

        $this->assertSame($expected, $absForkEval);
    }

    /**
     * @test
     */
    public function N_B_vs_b_b()
    {
        $board = (new StrToBoard('8/3k4/2bb4/8/8/4BN2/4K3/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 1,
        ];

        $absForkEval = (new BishopPairEvaluation($board))->eval();

        $this->assertSame($expected, $absForkEval);
    }
}
