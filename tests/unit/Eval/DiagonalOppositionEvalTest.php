<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DiagonalOppositionEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class DiagonalOppositionEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function position_01()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "The white king has the diagonal opposition preventing the advance of the other king.",
        ];

        $board = FenToBoardFactory::create('8/8/2K5/8/4k3/8/8/8 b - - 0 1');
        $directOppositionEval = new DiagonalOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->result);
        $this->assertSame($expectedExplanation, $directOppositionEval->explain());
    }

    /**
     * @test
     */
    public function position_02()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "The white king has the diagonal opposition preventing the advance of the other king.",
        ];

        $board = FenToBoardFactory::create('5k2/6p1/3K2P1/5P2/8/8/8/8 b - - 0 1');
        $directOppositionEval = new DiagonalOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->result);
        $this->assertSame($expectedExplanation, $directOppositionEval->explain());
    }

    /**
     * @test
     */
    public function position_03()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "The black king has the diagonal opposition preventing the advance of the other king.",
        ];

        $board = FenToBoardFactory::create('8/8/8/8/8/2K5/8/k7 w - - 0 1');
        $directOppositionEval = new DiagonalOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->result);
        $this->assertSame($expectedExplanation, $directOppositionEval->explain());
    }
}
