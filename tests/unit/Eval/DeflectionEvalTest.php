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
        $expectedElaboration = [
          "Black's king on e8 is deflected due to the bishop on f7, black's queen on d8 will be exposed to attack.",
        ];

        $board = (new StrToBoard('rnbqkb1r/pp2pBpp/5n2/8/4P3/2N5/PPP2PPP/R1BQK2R b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function deflection_with_multiple_pieces()
    {
        $expectedElaboration = [
          "Black's king on e8 is deflected due to the bishop on f7 and the rook on e1, black's queen on d8 will be exposed to attack.",
        ];

        $board = (new StrToBoard('rnbqkb1r/pp3Bpp/5n2/8/8/2N5/PPP2PPP/R1BQR1K1 b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function optional_deflection()
    {
        $expectedElaboration = [
          "If Black's king on d8 is deflected due to White's queen on d2, black's queen on e7 may well be exposed to attack.",
        ];

        $board = (new StrToBoard('3k4/4q3/8/8/8/8/3QR3/3K4 b - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function back_rank_deflection()
    {
        $expectedElaboration = [
          "White's king on g1 is deflected due to the rook on h1, white's queen on f1 will be exposed to attack.",
        ];

        $board = (new StrToBoard('2r5/5pk1/6p1/8/8/4RR2/5PP1/2q2QKr w - - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function rook_deflection_for_pawn_promotion()
    {
        $expectedElaboration = [
          "If the rook on d5 is deflected due to the rook on g5, the pawn on d7 is not threatened and may well be advanced for promotion.",
        ];

        $board = (new StrToBoard('8/3P4/2P5/k2r2R1/8/8/5K2/8 b - - 1 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function dev_test()
    {
        $board = (new StrToBoard('2b2Rk1/4q2p/3r2pQ/8/8/3r3R/6PP/6K1 b - - 1 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        print_r("*****DEV_TEST*****");
        print_r($deflectionEval->getElaboration());
        print_r("*****DEV_TEST*****");
        $this->assertSame(true, true);
    }

}
