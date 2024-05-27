<?php

namespace Chess\Tests\Unit;

use Chess\EvalFactory;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FEN\StrToBoard;

class EvalFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function classical_foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $board = (new StrToBoard('8/8/5k1n/6P1/7K/8/8/8 w - -'))
            ->create();

        $eval = EvalFactory::create('foo', $board);
    }

    /**
     * @test
     */
    public function absolute_fork()
    {
        $board = (new StrToBoard('8/8/5k1n/6P1/7K/8/8/8 w - -'))
            ->create();

        $expectedResult = [
            'w' => 3.2,
            'b' => 0,
        ];

        $expectedPhrase = [
            "Absolute fork attack on the knight on h6.",
        ];

        $eval = EvalFactory::create('Absolute fork', $board);

        $this->assertSame($expectedResult, $eval->getResult());
        $this->assertSame($expectedPhrase, $eval->getElaboration());
    }

    /**
     * @test
     */
    public function absolute_pin()
    {
        $board = (new StrToBoard('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'))
            ->create();

        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedPhrase = [
            "The knight on c6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
        ];

        $eval = EvalFactory::create('Absolute pin', $board);

        $this->assertSame($expectedResult, $eval->getResult());
        $this->assertSame($expectedPhrase, $eval->getElaboration());
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

        $board = (new StrToBoard('8/3qk3/8/3b4/4KR2/5Q2/8/8 w - - 0 1'))
            ->create();

        $eval = EvalFactory::create('Absolute skewer', $board);

        $this->assertSame($expectedResult, $eval->getResult());
        $this->assertSame($expectedPhrase, $eval->getElaboration());
    }
}
