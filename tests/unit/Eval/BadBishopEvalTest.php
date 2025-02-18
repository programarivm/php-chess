<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\BadBishopEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class BadBishopEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function position_01()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 5,
        ];

        $expectedExplanation = [
            "Black has a bad bishop because too many of its pawns are blocking it.",
        ];

        $board = FenToBoardFactory::create('8/5b2/p2k4/1p1p1p1p/1P1K1P1P/2P1PB2/8/8 w - - 0 1');
        $badBishopEval = new BadBishopEval($board);

        $this->assertSame($expectedResult, $badBishopEval->result);
        $this->assertSame($expectedExplanation, $badBishopEval->explain());
    }

    /**
     * @test
     */
    public function position_02()
    {
        $expectedResult = [
            'w' => 7,
            'b' => 4,
        ];

        $expectedExplanation = [
            "White has a bishop which is not too good because a few of its pawns are blocking it.",
        ];

        $board = FenToBoardFactory::create('2bqr3/r4pkp/1n1p2p1/2pP4/ppP1PQ2/1P3N1P/P1B2RP1/5RK1 w - - 0 1');
        $badBishopEval = new BadBishopEval($board);

        $this->assertSame($expectedResult, $badBishopEval->result);
        $this->assertSame($expectedExplanation, $badBishopEval->explain());
    }

    /**
     * @test
     */
    public function position_03()
    {
        $expectedResult = [
            'w' => 2,
            'b' => 5,
        ];

        $expectedExplanation = [
            "Black has a bishop which is not too good because a few of its pawns are blocking it.",
        ];

        $board = FenToBoardFactory::create('2r1k2r/1p1bnpp1/pq2p2p/3pP3/PP1N1P2/2PB4/6PP/R3QR1K w - - 0 1');
        $badBishopEval = new BadBishopEval($board);

        $this->assertSame($expectedResult, $badBishopEval->result);
        $this->assertSame($expectedExplanation, $badBishopEval->explain());
    }

    /**
     * @test
     */
    public function position_04()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 6,
        ];

        $expectedExplanation = [
            "Black has a bad bishop because too many of its pawns are blocking it.",
        ];

        $board = FenToBoardFactory::create('2b3k1/3nqr1p/2p1p1p1/1pPpP3/1P1Q1P2/8/1N2B1PP/R5K1 w - - 0 1');
        $badBishopEval = new BadBishopEval($board);

        $this->assertSame($expectedResult, $badBishopEval->result);
        $this->assertSame($expectedExplanation, $badBishopEval->explain());
    }

    /**
     * @test
     */
    public function position_05()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $board = FenToBoardFactory::create('3k4/5RN1/4P3/5P2/7K/8/8/6q1 b - -');
        $badBishopEval = new BadBishopEval($board);

        $this->assertSame($expectedResult, $badBishopEval->result);
    }
}
