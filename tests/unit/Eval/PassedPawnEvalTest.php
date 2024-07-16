<?php

namespace Chess\Tests\Unit\Eval;

use Chess\FenToBoardFactory;
use Chess\Eval\PassedPawnEval;
use Chess\Tests\AbstractUnitTestCase;

class PassedPawnEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kaufman_13()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['d5'],
        ];

        $expectedPhrase = [
            "Black has a slight passed pawn advantage.",
        ];

        $board = FenToBoardFactory::create('1r4k1/7p/5np1/3p3n/8/2NB4/7P/3N1RK1 w - -');
        
        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getExplanation());
    }

    /**
     * @test
     */
    public function kaufman_14()
    {
        $expectedResult = [
            'w' => ['c2'],
            'b' => [],
        ];

        $expectedPhrase = [
            "White has a slight passed pawn advantage.",
        ];

        $board = FenToBoardFactory::create('1r2r1k1/p4p1p/6pB/q7/8/3Q2P1/PbP2PKP/1R3R2 w - -');

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getExplanation());
    }

    /**
     * @test
     */
    public function kaufman_21()
    {
        $expectedResult = [
            'w' => [],
            'b' => ['e6', 'f5'],
        ];

        $expectedPhrase = [
            "Black has a moderate passed pawn advantage.",
        ];

        $board = FenToBoardFactory::create('8/2k5/4p3/1nb2p2/2K5/8/6B1/8 w - -');

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getExplanation());
    }

    /**
     * @test
     */
    public function a4()
    {
        $expectedResult = [
            'w' => ['a4'],
            'b' => [],
        ];

        $expectedPhrase = [
            "White has a slight passed pawn advantage.",
        ];

        $board = FenToBoardFactory::create('8/8/8/5k2/P7/4K3/8/8 w - - 0 1');

        $passedPawnEval = new PassedPawnEval($board);

        $this->assertSame($expectedResult, $passedPawnEval->getResult());
        $this->assertSame($expectedPhrase, $passedPawnEval->getExplanation());
    }
}
