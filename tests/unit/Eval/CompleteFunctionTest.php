<?php

namespace Chess\Tests\Unit\Function;

use Chess\Eval\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FenToBoardFactory;

class CompleteFunctionTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function names()
    {
        $expected = [
            'Material',
            'Center',
            'Connectivity',
            'Space',
            'Pressure',
            'King safety',
            'Protection',
            'Discovered check',
            'Doubled pawn',
            'Passed pawn',
            'Advanced pawn',
            'Far-advanced pawn',
            'Isolated pawn',
            'Backward pawn',
            'Defense',
            'Absolute skewer',
            'Absolute pin',
            'Relative pin',
            'Absolute fork',
            'Relative fork',
            'Outpost square',
            'Knight outpost',
            'Bishop outpost',
            'Bishop pair',
            'Bad bishop',
            'Diagonal opposition',
            'Direct opposition',
            'Overloading',
            'Back-rank threat',
            'Flight square',
            'Attack',
            'Checkability',
        ];

        $this->assertSame($expected, CompleteFunction::names());
    }

    /**
     * @test
     */
    public function classical_foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $board = FenToBoardFactory::create('8/8/5k1n/6P1/7K/8/8/8 w - -');

        $eval = CompleteFunction::evaluate('foo', $board);
    }

    /**
     * @test
     */
    public function absolute_fork()
    {
        $expectedResult = [
            'w' => 3.2,
            'b' => 0,
        ];

        $expectedElaboration = [
            "The pawn on g5 is attacking both the knight on h6 and the opponent's king at the same time.",
        ];

        $board = FenToBoardFactory::create('8/8/5k1n/6P1/7K/8/8/8 w - -');

        $eval = CompleteFunction::evaluate('Absolute fork', $board);

        $this->assertSame($expectedResult, $eval->result);
        $this->assertSame($expectedElaboration, $eval->elaborate());
    }

    /**
     * @test
     */
    public function absolute_pin()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedElaboration = [
            "The knight on c6 is pinned shielding the king so it cannot move out of the line of attack of the bishop on b5 because the king would be put in check.",
        ];

        $board = FenToBoardFactory::create('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -');

        $eval = CompleteFunction::evaluate('Absolute pin', $board);

        $this->assertSame($expectedResult, $eval->result);
        $this->assertSame($expectedElaboration, $eval->elaborate());
    }

    /**
     * @test
     */
    public function absolute_skewer()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedElaboration = [
            "When White's king on e4 will be moved, a piece that is more valuable than the bishop on d5 may well be exposed to attack.",
        ];


        $board = FenToBoardFactory::create('8/3qk3/8/3b4/4KR2/5Q2/8/8 w - - 0 1');

        $eval = CompleteFunction::evaluate('Absolute skewer', $board);

        $this->assertSame($expectedResult, $eval->result);
        $this->assertSame($expectedElaboration, $eval->elaborate());
    }

        /**
     * @test
     */
    public function kaufman_06()
    {
        $expectedNormd = [ -1.0, -0.3333, 0.0112, 0.0865, 0.0865, 0.0865, 0.4325, 1.0 ];
        $expectedSteinitz = 4;
        $expectedMean = 0.0462;
        $expectedMedian = 0.0865;
        $expectedMode = 0.0865;
        $expectedVar = 0.288;
        $expectedSd = 0.5367;

        $board = FenToBoardFactory::create('r5k1/3n1ppp/1p6/3p1p2/3P1B2/r3P2P/PR3PP1/2R3K1 b - -');

        $normd = array_filter(CompleteFunction::normalization($board));
        sort($normd);

        $steinitz = CompleteFunction::steinitz($board);
        $mean = CompleteFunction::mean($board);
        $median = CompleteFunction::median($board);
        $mode = CompleteFunction::mode($board);
        $var = CompleteFunction::var($board);
        $sd = CompleteFunction::sd($board);

        $this->assertSame($expectedNormd, $normd);
        $this->assertSame($expectedSteinitz, $steinitz);
        $this->assertSame($expectedMean, $mean);
        $this->assertSame($expectedMedian, $median);
        $this->assertSame($expectedMode, $mode);
        $this->assertSame($expectedVar, $var);
        $this->assertSame($expectedSd, $sd);
    }
}
