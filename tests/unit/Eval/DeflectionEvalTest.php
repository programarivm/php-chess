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
          "White's king on g1 is deflected due to the rook on h1, these pieces will be exposed to attack: White's queen on f1, the pawn on f2; threatning a check.",
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
          "If the rook on d5 is deflected due to the rook on g5, the pawn on d7 is not attacked by it and may well be advanced for promotion.",
        ];

        $board = (new StrToBoard('8/3P4/2P5/k2r2R1/8/8/5K2/8 b - - 1 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function queen_deflection_for_protecting_multiple_advanced_pawns()
    {
        $expectedElaboration = [
          "If Black's queen on d5 is deflected due to the rook on h5, these pawns are not attacked by it and may well be advanced for promotion: the pawn on d7, the pawn on c6.",
        ];

        $board = (new StrToBoard('8/3P4/2P5/k2q3R/8/8/5K2/8 b - - 1 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function deflection_for_exposing_king_with_checkmate()
    {
        $expectedElaboration = [
          "Black's queen on e7 is deflected due to the rook on f8, the pawn on h7 will be exposed to attack; threatning checkmate.",
        ];

        $board = (new StrToBoard('2b2Rk1/4q2p/3r2pQ/8/8/3r3R/6PP/6K1 b - - 1 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function knight_deflection_with_check()
    {
        $expectedElaboration = [
          "White's queen on e3 is deflected due to the knight on f3, the rook on e1 will be exposed to attack; threatning a check.",
        ];

        $board = (new StrToBoard('4r1k1/5p2/3q2pp/8/6P1/4QnNP/5P2/4R1K1 w - - 1 2'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    /**
     * @test
     */
    public function no_deflection()
    {
        $expectedElaboration = [];

        $board = (new StrToBoard('r1bqkb1r/2pp1ppp/p1n2n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQ1RK1 w Qkq - 0 1'))
          ->create();

        $deflectionEval = new DeflectionEval($board);

        $this->assertSame($expectedElaboration, $deflectionEval->getElaboration());
    }

    // /**
    //  * @test
    //  */
    // public function extension_with_checking_unguarded_squares()
    // {
    //     $board = (new StrToBoard('2R3k1/1q5p/4P1pQ/5p2/8/8/1B2r1PP/1K6 b - - 1 1'))
    //       ->create();

    //     $deflectionEval = new DeflectionEval($board);

    //     print_r("*****DEV_TEST*****");
    //     print_r($deflectionEval->getElaboration());

    //     print_r("*****DEV_TEST*****");
    //     $this->assertSame(true, true);
    // }
}
