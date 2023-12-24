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
    public function endgame()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedPhrase = [
            "The pawn on d4 is being threatened and may be lost if not defended properly.",
        ];

        $board = (new StrToBoard('6k1/6p1/2n2b2/8/3P4/5N2/2K5/8 w - -'))->create();
        $threatEval = new ThreatEval($board);

        $this->assertSame($expectedResult, $threatEval->getResult());
        $this->assertSame($expectedPhrase, $threatEval->getPhrases());
    }
}
