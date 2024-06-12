<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\CheckmateInOneEval;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\FEN\StrToBoard;

class CheckmateInOneEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function b25()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
        $board = (new SanPlay($B25))->validate()->getBoard();
        $checkmateInOneEval = new CheckmateInOneEval($board);

        $this->assertSame($expectedResult, $checkmateInOneEval->getResult());
        $this->assertSame($expectedExplanation, $checkmateInOneEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkmateInOneEval->getElaboration());
    }

    /**
     * @test
     */
    public function scholar_chechmate()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White could checkmate in one move.",
        ];

        $expectedElaboration = [
            "White threatens to play Qxf7# delivering checkmate in one move.",
        ];

        $board = (new StrToBoard('r1bqkbnr/pppp1ppp/2n5/4p2Q/2B1P3/8/PPPP1PPP/RNB1K1NR b KQkq -'))->create();
        $checkmateInOneEval = new CheckmateInOneEval($board);

        $this->assertSame($expectedResult, $checkmateInOneEval->getResult());
        $this->assertSame($expectedExplanation, $checkmateInOneEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkmateInOneEval->getElaboration());
    }

    /**
     * @test
     */
    public function two_knights_checkmate()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White could checkmate in one move.",
        ];

        $expectedElaboration = [
            "White threatens to play Nf7# delivering checkmate in one move.",
        ];

        $board = (new StrToBoard('7k/8/5NKN/8/8/8/8/4q3 b - -'))->create();
        $checkmateInOneEval = new CheckmateInOneEval($board);

        $this->assertSame($expectedResult, $checkmateInOneEval->getResult());
        $this->assertSame($expectedExplanation, $checkmateInOneEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkmateInOneEval->getElaboration());
    }

    /**
     * @test
     */
    public function rook_checkmated()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 0,
        ];

        $expectedExplanation = [];

        $expectedElaboration = [];

        $board = (new StrToBoard('1k6/8/8/8/8/8/5PPP/1r5K w - -'))->create();
        $checkmateInOneEval = new CheckmateInOneEval($board);

        $this->assertSame($expectedResult, $checkmateInOneEval->getResult());
        $this->assertSame($expectedExplanation, $checkmateInOneEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkmateInOneEval->getElaboration());
    }

    /**
     * @test
     */
    public function fool_chechmate_a6()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black could checkmate in one move.",
        ];

        $expectedElaboration = [
            "Black threatens to play Qh4# delivering checkmate in one move.",
        ];

        $board = (new StrToBoard('rnbqkbnr/1ppp1ppp/p7/4p3/6P1/5P2/PPPPP2P/RNBQKBNR w KQkq -'))->create();
        $checkmateInOneEval = new CheckmateInOneEval($board);

        $this->assertSame($expectedResult, $checkmateInOneEval->getResult());
        $this->assertSame($expectedExplanation, $checkmateInOneEval->getExplanation());
        $this->assertSame($expectedElaboration, $checkmateInOneEval->getElaboration());
    }
}
