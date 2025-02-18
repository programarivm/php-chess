<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AbsoluteSkewerEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

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

        $expectedElaboration = [
            "When White's king on e4 will be moved, a piece that is more valuable than the bishop on d5 may well be exposed to attack.",
        ];

        $board = FenToBoardFactory::create('8/3qk3/8/3b4/4KR2/5Q2/8/8 w - - 0 1');

        $absoluteSkewerEval = new AbsoluteSkewerEval($board);

        $this->assertSame($expectedResult, $absoluteSkewerEval->result);
        $this->assertSame($expectedElaboration, $absoluteSkewerEval->elaborate());
    }

    /**
     * @test
     */
    public function black_king_skewered_white_bishop()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedElaboration = [
            "When Black's king on f6 will be moved, a piece that is more valuable than the bishop on e5 may well be exposed to attack.",
        ];

        $board = FenToBoardFactory::create('2Q5/1p4q1/p4k2/4B1p1/P3b3/7P/5PP1/6K1 b - - 0 1');

        $absoluteSkewerEval = new AbsoluteSkewerEval($board);

        $this->assertSame($expectedResult, $absoluteSkewerEval->result);
        $this->assertSame($expectedElaboration, $absoluteSkewerEval->elaborate());
    }
}
