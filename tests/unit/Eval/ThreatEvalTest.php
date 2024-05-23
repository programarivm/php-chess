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
            'w' => 4,
            'b' => 9.99,
        ];

        $expectedExplanation = [
            "Black has a total threat advantage.",
        ];

        $board = (new StrToBoard('r1bqkbnr/5ppp/p1npp3/1p6/2B1P3/2N2N2/PP2QPPP/R1B2RK1 w kq b6'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
    }

    /**
     * @test
     */
    public function middlegame()
    {
        $expectedResult = [
            'w' => 8.4,
            'b' => 9.86,
        ];

        $expectedExplanation = [
            "Black has a slight threat advantage.",
        ];

        $board = (new StrToBoard('r1bqkbnr/5ppp/p1npp3/1n6/2B1P3/2N2N2/PP2QPPP/R1B2RK1 w kq b6'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expectedResult = [
            'w' => 3.2,
            'b' => 4.2,
        ];

        $expectedExplanation = [
            "Black has a slight threat advantage.",
        ];

        $board = (new StrToBoard('6k1/6p1/2n2b2/8/3P4/5N2/2K5/8 w - -'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedExplanation, $threatEval->getExplanation());
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
