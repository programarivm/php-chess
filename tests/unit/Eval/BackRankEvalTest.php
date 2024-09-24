<?php

namespace Chess\Tests\unit\Eval;

use Chess\Eval\BackRankEval;
use Chess\FenToBoardFactory;
use Chess\Tests\AbstractUnitTestCase;

class BackRankEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function white_checkmated_on_the_back_rank()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['g8'],
        ];
        $expectedElaboration = ["Black's king on g8 is vulnerable to back-rank checkmate."];

        $board = FenToBoardFactory::create('R5k1/5ppp/4p3/1r6/6P1/3R1P2/4P1P1/4K3 b KQkq - 0 1');
        $backRankEval = new BackRankEval($board);

        $this->assertSame($expectedResult, $backRankEval->getResult());
        $this->assertSame($expectedElaboration, $backRankEval->getElaboration());
    }

    /**
     * @test
     */
    public function white_checkmated_on_the_back_rank_with_an_escape()
    {
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];
        $expectedElaboration = [];

        $board = FenToBoardFactory::create('R5k1/5p1p/4p1p1/1r6/6P1/3R1P2/4P1P1/4K3 w KQkq - 0 1');
        $backRankEval = new BackRankEval($board);

        $this->assertSame($expectedResult, $backRankEval->getResult());
        $this->assertSame($expectedElaboration, $backRankEval->getElaboration());
    }

    /**
     * @test
     */
    public function d1_checkmate()
    {
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];
        $expectedElaboration = [];

        $board = FenToBoardFactory::create('3r4/k7/8/8/8/8/5PPP/6K1 w - -');
        $backRankEval = new BackRankEval($board);

        $this->assertSame($expectedResult, $backRankEval->getResult());
        $this->assertSame($expectedElaboration, $backRankEval->getElaboration());
    }

    /**
     * @test
     */
    public function h8_checkmate()
    {
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];
        $expectedElaboration = [];

        $board = FenToBoardFactory::create('4k3/3ppp2/8/8/8/8/6K1/7R b - -');
        $backRankEval = new BackRankEval($board);

        $this->assertSame($expectedResult, $backRankEval->getResult());
        $this->assertSame($expectedElaboration, $backRankEval->getElaboration());
    }
}
