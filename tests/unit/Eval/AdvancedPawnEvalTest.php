<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AdvancedPawnEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class AdvancedPawnEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function b6()
    {
        $expectedResult = [
            'w' => ['b6'],
            'b' => [],
        ];

        $expectedExplanation = [
            "White has more advanced pawns in its favor.",
        ];

        $expectedElaboration = [
            "These are advanced pawns: b6.",
        ];

        $board = FenToBoardFactory::create('8/1p6/1P1K4/pk6/8/8/5B2/8 b - - 3 56');

        $advancedPawnEval = new AdvancedPawnEval($board);

        $this->assertSame($expectedResult, $advancedPawnEval->result);
        $this->assertSame($expectedExplanation, $advancedPawnEval->explain());
        $this->assertSame($expectedElaboration, $advancedPawnEval->elaborate());
    }

    /**
     * @test
     */
    public function e6_c3_e2()
    {
        $expectedResult = [
            'w' => ['e6'],
            'b' => ['c3', 'e2'],
        ];

        $expectedExplanation = [
            "Black has more advanced pawns in its favor.",
        ];

        $expectedElaboration = [
            "These are advanced pawns: e6, c3, e2.",
        ];

        $board = FenToBoardFactory::create('8/8/4P3/4K3/8/2p2k2/4p3/8 w - - 0 1');

        $advancedPawnEval = new AdvancedPawnEval($board);

        $this->assertSame($expectedResult, $advancedPawnEval->result);
        $this->assertSame($expectedExplanation, $advancedPawnEval->explain());
        $this->assertSame($expectedElaboration, $advancedPawnEval->elaborate());
    }
}
