<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DirectOppositionEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class DirectOppositionEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function position_01()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $board = (new StrToBoard('8/8/2k5/8/5K2/8/8/8 w - - 0 1'))->create();
        $result = (new DirectOppositionEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function position_02()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "The black king has the direct opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/8/2k5/8/2K5/8/8/8 w - - 0 1'))->create();
        $directOppositionEval = new DirectOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedExplanation, $directOppositionEval->getExplanation());
    }

    /**
     * @test
     */
    public function position_03()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "The white king has the direct opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/8/2k5/8/2K5/8/8/8 b - - 0 1'))->create();
        $directOppositionEval = new DirectOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedExplanation, $directOppositionEval->getExplanation());
    }

    /**
     * @test
     */
    public function position_04()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $board = (new StrToBoard('8/2k5/8/8/2K5/8/8/8 w - - 0 1'))->create();
        $result = (new DirectOppositionEval($board))->getResult();

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function position_05()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "The white king has the direct opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/8/8/8/8/k7/8/K7 b - - 0 1'))->create();
        $directOppositionEval = new DirectOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedExplanation, $directOppositionEval->getExplanation());
    }

    /**
     * @test
     */
    public function position_06()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "The white king has the direct opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/8/5k1K/8/7p/8/8/8 b - - 0 1'))->create();
        $directOppositionEval = new DirectOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedExplanation, $directOppositionEval->getExplanation());
    }

    /**
     * @test
     */
    public function position_07()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "The black king has the direct opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/8/4k1K1/8/7p/8/8/8 w - -'))->create();
        $directOppositionEval = new DirectOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedExplanation, $directOppositionEval->getExplanation());
    }

    /**
     * @test
     */
    public function position_08()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "The white king has the direct opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/5k2/8/5K2/7p/8/8/8 b - -'))->create();
        $directOppositionEval = new DirectOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedExplanation, $directOppositionEval->getExplanation());
    }

    /**
     * @test
     */
    public function position_09()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "The black king has the direct opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/5k2/8/5K2/8/7p/8/8 w - -'))->create();
        $directOppositionEval = new DirectOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedExplanation, $directOppositionEval->getExplanation());
    }
}
