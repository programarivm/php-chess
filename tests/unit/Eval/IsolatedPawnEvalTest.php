<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\IsolatedPawnEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\FenToBoardFactory as CapablancaFenToBoardFactory;
use Chess\Variant\Classical\FenToBoardFactory as ClassicalFenToBoardFactory;

class IsolatedPawnEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kaufman_09()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['a7', 'd5'],
        ];

        $expectedExplanation = [
            "Black has more isolated pawns against than its opponent.",
        ];

        $expectedElaboration = [
            "The following are isolated pawns: a7, d5.",
        ];

        $board = ClassicalFenToBoardFactory::create('r3k2r/pbn2ppp/8/1P1pP3/P1qP4/5B2/3Q1PPP/R3K2R w KQkq -');
        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->result);
        $this->assertSame($expectedExplanation, $isolatedPawnEval->explain());
        $this->assertSame($expectedElaboration, $isolatedPawnEval->elaborate());
    }

    /**
     * @test
     */
    public function kaufman_13()
    {
        $expectedResult = [
            'w' => ['h2'],
            'b' => ['d5'],
        ];

        $expectedExplanation = [];

        $expectedElaboration = [
            "The following are isolated pawns: d5, h2.",
        ];

        $board = ClassicalFenToBoardFactory::create('1r4k1/7p/5np1/3p3n/8/2NB4/7P/3N1RK1 w - -');
        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->result);
        $this->assertSame($expectedExplanation, $isolatedPawnEval->explain());
        $this->assertSame($expectedElaboration, $isolatedPawnEval->elaborate());
    }

    /**
     * @test
     */
    public function kaufman_14()
    {
        $expectedResult = [
            'w' => ['a2', 'c2'],
            'b' => ['a7'],
        ];

        $expectedExplanation = [
            "White has more isolated pawns against than its opponent.",
        ];

        $expectedElaboration = [
            "The following are isolated pawns: a7, a2, c2.",
        ];

        $board = ClassicalFenToBoardFactory::create('1r2r1k1/p4p1p/6pB/q7/8/3Q2P1/PbP2PKP/1R3R2 w - -');
        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->result);
        $this->assertSame($expectedExplanation, $isolatedPawnEval->explain());
        $this->assertSame($expectedElaboration, $isolatedPawnEval->elaborate());
    }

    /**
     * @test
     */
    public function capablanca_f4()
    {
        $expectedResult = [
            'w' => [],
            'b' => [],
        ];

        $board = CapablancaFenToBoardFactory::create('rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3');

        $isolatedPawnEval = new IsolatedPawnEval($board);

        $this->assertSame($expectedResult, $isolatedPawnEval->result);
    }
}
