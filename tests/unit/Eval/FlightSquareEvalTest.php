<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\FlightSquareEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

class FlightSquareEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $flightSquareEval = new FlightSquareEval(new Board());

        $this->assertSame($expectedResult, $flightSquareEval->result);
        $this->assertSame($expectedExplanation, $flightSquareEval->explain());
    }

    /**
     * @test
     */
    public function Rh2()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 3,
        ];

        $expectedExplanation = [
            "Black's king has more safe squares to move to than its counterpart.",
        ];

        $board = FenToBoardFactory::create('8/p3kp1p/1p2p1p1/2p5/2P3PP/1P3n2/P4r2/1B1R3K b - -');

        $flightSquareEval = new FlightSquareEval($board);

        $this->assertSame($expectedResult, $flightSquareEval->result);
        $this->assertSame($expectedExplanation, $flightSquareEval->explain());
    }

    /**
     * @test
     */
    public function Qa5()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black's king has more safe squares to move to than its counterpart.",
        ];

        $board = FenToBoardFactory::create('8/5R2/1p2pNpk/1P5p/1K6/1P1b4/8/q7 b - -');

        $flightSquareEval = new FlightSquareEval($board);

        $this->assertSame($expectedResult, $flightSquareEval->result);
        $this->assertSame($expectedExplanation, $flightSquareEval->explain());
    }
}
