<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\CheckabilityEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class CheckabilityEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function a1_checkable_w()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $board = FenToBoardFactory::create('1b5k/6pp/8/8/8/8/8/K7 w - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->result);
        $this->assertSame($expectedExplanation, $checkabilityEval->explain());
    }

    /**
     * @test
     */
    public function a1_checkable_b()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "White's king can be checked so it is vulnerable to forced moves.",
        ];

        $board = FenToBoardFactory::create('1b5k/6pp/8/8/8/8/8/K7 b - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->result);
        $this->assertSame($expectedExplanation, $checkabilityEval->explain());
    }

    /**
     * @test
     */
    public function h8_checkable_w()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "Black's king can be checked so it is vulnerable to forced moves.",
        ];

        $board = FenToBoardFactory::create('1b5k/7p/8/8/8/8/1K6/5R2 w - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->result);
        $this->assertSame($expectedExplanation, $checkabilityEval->explain());
    }

    /**
     * @test
     */
    public function b2_checkable_b()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "White's king can be checked so it is vulnerable to forced moves.",
        ];

        $board = FenToBoardFactory::create('1b5k/7p/8/8/8/8/1K6/5R2 b - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->result);
        $this->assertSame($expectedExplanation, $checkabilityEval->explain());
    }
}
