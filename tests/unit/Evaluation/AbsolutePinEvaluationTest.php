<?php

namespace Chess\Tests\Unit\Evaluation;

use Chess\Board;
use Chess\Evaluation\AbsolutePinEvaluation;
use Chess\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class AbsolutePinEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $board = (new StrToBoard('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 3.2,
        ];

        $absPinEval = (new AbsolutePinEvaluation($board))->eval();

        $this->assertSame($expected, $absPinEval);
    }

    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense_center_gambit_Bg4()
    {
        $board = (new StrToBoard('r2qkbnr/ppp2ppp/2np4/1B2p3/3PP1b1/5N2/PPP2PPP/RNBQK2R w KQkq -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 3.2,
        ];

        $absPinEval = (new AbsolutePinEvaluation($board))->eval();

        $this->assertSame($expected, $absPinEval);
    }

    /**
     * @test
     */
    public function both_knights_pinned()
    {
        $board = (new StrToBoard('r2qk1nr/ppp2ppp/2n5/1B1pp3/1b1PP1b1/2N1BN2/PPP2PPP/R2QK2R w KQkq -'))
            ->create();

        $expected = [
            'w' => 3.2,
            'b' => 3.2,
        ];

        $absPinEval = (new AbsolutePinEvaluation($board))->eval();

        $this->assertSame($expected, $absPinEval);
    }
}
