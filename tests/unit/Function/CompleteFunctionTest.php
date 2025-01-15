<?php

namespace Chess\Tests\Unit\Function;

use Chess\FenToBoardFactory;
use Chess\Function\CompleteFunction;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FEN\StrToBoard;

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
        $board = FenToBoardFactory::create('8/8/5k1n/6P1/7K/8/8/8 w - -');

        $expectedResult = [
            'w' => 3.2,
            'b' => 0,
        ];

        $expectedPhrase = [
            "Absolute fork attack on the knight on h6.",
        ];

        $eval = CompleteFunction::evaluate('Absolute fork', $board);

        $this->assertSame($expectedResult, $eval->result);
        $this->assertSame($expectedPhrase, $eval->elaborate());
    }

    /**
     * @test
     */
    public function absolute_pin()
    {
        $board = FenToBoardFactory::create('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -');

        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedPhrase = [
            "The knight on c6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
        ];

        $eval = CompleteFunction::evaluate('Absolute pin', $board);

        $this->assertSame($expectedResult, $eval->result);
        $this->assertSame($expectedPhrase, $eval->elaborate());
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

        $expectedPhrase = [
            "When White's king on e4 will be moved, a piece that is more valuable than the bishop on d5 may well be exposed to attack.",
        ];


        $board = FenToBoardFactory::create('8/3qk3/8/3b4/4KR2/5Q2/8/8 w - - 0 1');

        $eval = CompleteFunction::evaluate('Absolute skewer', $board);

        $this->assertSame($expectedResult, $eval->result);
        $this->assertSame($expectedPhrase, $eval->elaborate());
    }
}
