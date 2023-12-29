<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AbsoluteSkewerEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class AbsoluteSkewerEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedPhrase = [];

        $board = (new StrToBoard('8/3qk3/8/3b4/4KR2/5Q2/8/8 w - - 0 1'))
            ->create();

        $absoluteSkewerEval = new AbsoluteSkewerEval($board);

        $this->assertSame($expectedResult, $absoluteSkewerEval->getResult());
        $this->assertSame($expectedPhrase, $absoluteSkewerEval->getPhrases());
    }
}
