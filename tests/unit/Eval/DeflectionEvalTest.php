<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DeflectionEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class DeflectionEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function basic_test()
    {
        $expectedResult = [
          'w' => 8.8,
          'b' => 0,
        ];

        $expectedExplanation = [
          "White has a total deflection advantage.",
        ];

        $expectedElaboration = [
          "If Black's king on e8 is deflected due to the bishop on f7, black's queen on d8 may well be exposed to attack.",
        ];

        $board = (new StrToBoard('rnbqkb1r/pp2pBpp/5n2/8/4P3/2N5/PPP2PPP/R1BQK2R b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedResult, $deflectionEval->getResult());
        $this->assertSame($expectedExplanation, $deflectionEval->getExplanation());
        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

}
