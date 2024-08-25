<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\DeflectionEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class DeflectionEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function basic_deflection_test()
    {
        $expectedResult = [
          'w' => 8.8,
          'b' => 0,
        ];

        $expectedExplanation = [
          "White has a total deflection advantage.",
        ];

        $expectedElaboration = [
          "Black's king on e8 is deflected due to the bishop on f7, black's queen on d8 may well be exposed to attack.",
        ];

        $board = (new StrToBoard('rnbqkb1r/pp2pBpp/5n2/8/4P3/2N5/PPP2PPP/R1BQK2R b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedResult, $deflectionEval->getResult());
        $this->assertSame($expectedExplanation, $deflectionEval->getExplanation());
        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function deflection_with_multiple_pieces()
    {
        $expectedResult = [
          'w' => 8.8,
          'b' => 0,
        ];

        $expectedExplanation = [
          "White has a total deflection advantage.",
        ];

        $expectedElaboration = [
          "Black's king on e8 is deflected due to the bishop on f7 and the rook on e1, black's queen on d8 may well be exposed to attack.",
        ];

        $board = (new StrToBoard('rnbqkb1r/pp3Bpp/5n2/8/8/2N5/PPP2PPP/R1BQR1K1 b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedResult, $deflectionEval->getResult());
        $this->assertSame($expectedExplanation, $deflectionEval->getExplanation());
        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function moderate_deflection_advantage()
    {
        $expectedResult = [
          'w' => 3.33,
          'b' => 0,
        ];

        $expectedExplanation = [
          "White has a moderate deflection advantage.",
        ];

        $expectedElaboration = [
          "Black's king on e8 is deflected due to the bishop on f7, the bishop on d7 may well be exposed to attack.",
        ];

        $board = (new StrToBoard('4kb1r/pp1bpB1p/8/n7/8/2N5/PPP2PPP/3R1RK1 b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedResult, $deflectionEval->getResult());
        $this->assertSame($expectedExplanation, $deflectionEval->getExplanation());
        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function slight_deflection_advantage()
    {
        $expectedResult = [
          'w' => 1.0,
          'b' => 0,
        ];

        $expectedExplanation = [
          "White has a slight deflection advantage.",
        ];

        $expectedElaboration = [
          "Black's king on e8 is deflected due to the bishop on f7, the pawn on d7 may well be exposed to attack.",
        ];

        $board = (new StrToBoard('4kb1r/p2ppB1p/8/n7/8/2N5/PPP2PPP/3R1RK1 b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedResult, $deflectionEval->getResult());
        $this->assertSame($expectedExplanation, $deflectionEval->getExplanation());
        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function dev_test()
    {
        $board = (new StrToBoard('4kb1r/p2ppB1p/8/n7/8/2N5/PPP2PPP/3R1RK1 b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        print_r("*****DEV_TEST*****");
        print_r($deflectionEval->getResult());
        print_r($deflectionEval->getExplanation());
        print_r($deflectionEval->getElaboration());
        print_r("*****DEV_TEST*****");
        $this->assertSame(true, true);
    }

}
