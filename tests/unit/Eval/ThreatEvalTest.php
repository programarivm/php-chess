<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\ThreatEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class ThreatEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function B21()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 2.33,
        ];

        $expectedExplanation = [
            "Black has a slight threat advantage.",
        ];

        $expectedElaboration = [
            "The c4-square is under threat of being attacked.",
        ];

        $board = (new StrToBoard('r1bqkbnr/5ppp/p1npp3/1p6/2B1P3/2N2N2/PP2QPPP/R1B2RK1 w kq b6'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
        $this->assertSame($expectedElaboration, $threatEval->getElaboration());
    }

    /**
     * @test
     */
    public function B21_Bb3()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = (new StrToBoard('r1bqkbnr/5ppp/p1npp3/1p6/4P3/1BN2N2/PP2QPPP/R1B2RK1 b kq -'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
        $this->assertSame($expectedElaboration, $threatEval->getElaboration());
    }

    /**
     * @test
     */
    public function middlegame()
    {
        $expectedResult = [
            'w' => 0.8700000000000001,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a slight threat advantage.",
        ];

        $expectedElaboration = [
            "The b5-square is under threat of being attacked.",
        ];

        $board = (new StrToBoard('r1bqkbnr/5ppp/p1npp3/1n6/2B1P3/2N2N2/PP2QPPP/R1B2RK1 w kq b6'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
        $this->assertSame($expectedElaboration, $threatEval->getElaboration());
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1.0,
        ];

        $expectedExplanation = [
            "Black has a slight threat advantage.",
        ];

        $expectedElaboration = [
            "The d4-square is under threat of being attacked.",
        ];

        $board = (new StrToBoard('6k1/6p1/2n2b2/8/3P4/5N2/2K5/8 w - -'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
        $this->assertSame($expectedElaboration, $threatEval->getElaboration());
    }

    /**
     * @test
     */
    public function w_N_c2()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedExplanation = [
            "Black has a moderate threat advantage.",
        ];

        $board = (new StrToBoard('2r3k1/8/8/2q5/8/8/2N5/1K6 w - -'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
    }
}
