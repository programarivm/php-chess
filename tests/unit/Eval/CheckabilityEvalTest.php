<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\CheckabilityEval;
use Chess\Tests\AbstractUnitTestCase;

class CheckabilityEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function a1_checkable_w()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has a checkability advantage.",
        ];

        $expectedElaboration = [
            "White's king on a1 can be checked.",
        ];

        $board = FenToBoardFactory::create('1b5k/6pp/8/8/8/8/8/K7 w - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->getResult());
        $this->assertSame($expectedExplanation, $checkabilityEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkabilityEval->getElaboration());
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
            "Black has a checkability advantage.",
        ];

        $expectedElaboration = [
            "White's king on a1 can be checked.",
        ];

        $board = FenToBoardFactory::create('1b5k/6pp/8/8/8/8/8/K7 b - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->getResult());
        $this->assertSame($expectedExplanation, $checkabilityEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkabilityEval->getElaboration());
    }

    /**
     * @test
     */
    public function a1_and_h8_checkable_w()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 1,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
            "Black's king on h8 can be checked.",
            "White's king on a1 can be checked.",
        ];

        $board = FenToBoardFactory::create('1b5k/7p/8/8/8/8/8/K4R2 w - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->getResult());
        $this->assertSame($expectedExplanation, $checkabilityEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkabilityEval->getElaboration());
    }

    /**
     * @test
     */
    public function a1_and_h8_checkable_b()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 1,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
            "Black's king on h8 can be checked.",
            "White's king on a1 can be checked.",
        ];

        $board = FenToBoardFactory::create('1b5k/7p/8/8/8/8/8/K4R2 b - -');
        $checkabilityEval = new CheckabilityEval($board);

        $this->assertSame($expectedResult, $checkabilityEval->getResult());
        $this->assertSame($expectedExplanation, $checkabilityEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkabilityEval->getElaboration());
    }
}
