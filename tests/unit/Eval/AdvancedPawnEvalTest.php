<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoard;
use Chess\Eval\AdvancedPawnEval;
use Chess\Tests\AbstractUnitTestCase;

class AdvancedPawnEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function a4()
    {
        $expectedResult = [
            'w' => ['b6'],
            'b' => [],
        ];

        $expectedPhrase = [
            "b6 is an advanced pawn.",
        ];

        $board = FenToBoard::create('8/1p6/1P1K4/pk6/8/8/5B2/8 b - - 3 56');

        $advancedPawnEval = new AdvancedPawnEval($board);

        $this->assertSame($expectedResult, $advancedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $advancedPawnEval->getPhrases());
    }
}
