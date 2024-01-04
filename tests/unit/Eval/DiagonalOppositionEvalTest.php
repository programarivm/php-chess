<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DiagonalOppositionEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

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

        $expectedPhrase = [
            "The white king has the diagonal opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('8/8/2K5/8/4k3/8/8/8 b - - 0 1'))->create();
        $directOppositionEval = new DiagonalOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedPhrase, $directOppositionEval->getPhrases());
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

        $expectedPhrase = [
            "The white king has the diagonal opposition preventing the advance of the other king.",
        ];

        $board = (new StrToBoard('5k2/6p1/3K2P1/5P2/8/8/8/8 b - - 0 1'))->create();
        $directOppositionEval = new DiagonalOppositionEval($board);

        $this->assertSame($expectedResult, $directOppositionEval->getResult());
        $this->assertSame($expectedPhrase, $directOppositionEval->getPhrases());
    }
}
