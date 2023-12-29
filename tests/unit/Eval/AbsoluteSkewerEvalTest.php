<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AbsoluteSkewerEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class AbsoluteSkewerEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function white_king_skewered_black_bishop()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedPhrase = [
            "When White's king on e4 will be moved, a piece that is more valuable than the bishop on d5 will be exposed to attack.",
        ];

        $board = (new StrToBoard('8/3qk3/8/3b4/4KR2/5Q2/8/8 w - - 0 1'))
            ->create();

        $absoluteSkewerEval = new AbsoluteSkewerEval($board);

        $this->assertSame($expectedResult, $absoluteSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $absoluteSkewerEval->getPhrases());
    }
}
