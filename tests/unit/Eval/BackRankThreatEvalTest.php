<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\BackRankThreatEval;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class BackRankThreatEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function b8_checkmate()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a back-rank checkmate advantage.",
        ];

        $expectedElaboration = [
            "Black's king on g8 may soon need to be guarded against back-rank threats.",
        ];

        $board = FenToBoardFactory::create('6k1/R4ppp/4p3/1r6/6P1/3R1P2/4P1P1/4K3 b KQkq -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function d1_checkmate()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has a back-rank checkmate advantage.",
        ];

        $expectedElaboration = [
            "White's king on g1 may soon need to be guarded against back-rank threats.",
        ];

        $board = FenToBoardFactory::create('3r4/k7/8/8/8/8/5PPP/6K1 w - -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function h8_checkmate()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has a back-rank checkmate advantage.",
        ];

        $expectedElaboration = [
            "Black's king on e8 may soon need to be guarded against back-rank threats.",
        ];

        $board = FenToBoardFactory::create('4k3/3ppp2/8/8/8/8/6K1/7R b - -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function d1_and_h8_checkmates()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 1,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
            "Black's king on e8 may soon need to be guarded against back-rank threats.",
            "White's king on b1 may soon need to be guarded against back-rank threats.",
        ];

        $board = FenToBoardFactory::create('4k3/3ppp2/8/8/6q1/8/PPP4R/1K6 w - -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function is_not_deliverable()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('4k3/3ppp2/8/8/8/8/6K1/7N b - -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function A40()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('rnbqk2r/ppppppbp/5np1/8/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function e4()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function a1_checkmate()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has a back-rank checkmate advantage.",
        ];

        $expectedElaboration = [
            "White's king on e1 may soon need to be guarded against back-rank threats.",
        ];

        $board = FenToBoardFactory::create('4r3/4k3/8/8/8/8/3PPP2/4K3 b - -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function h1_checkmate()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has a back-rank checkmate advantage.",
        ];

        $expectedElaboration = [
            "White's king on e1 may soon need to be guarded against back-rank threats.",
        ];

        $board = FenToBoardFactory::create('4r3/4k3/8/8/B7/8/3PPP2/4K3 b - -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }

    /**
     * @test
     */
    public function guarded_king()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [
        ];

        $expectedElaboration = [
        ];

        $board = FenToBoardFactory::create('4r3/4k3/8/8/B7/6N1/3PPP2/4K3 b - -');
        $backRankEval = new BackRankThreatEval($board);

        $this->assertSame($expectedResult, $backRankEval->result);
        $this->assertSame($expectedExplanation, $backRankEval->explain());
        $this->assertSame($expectedElaboration, $backRankEval->elaborate());
    }
}
