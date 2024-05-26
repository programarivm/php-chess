<?php

namespace Chess\Tests\Unit\ML\Supervised\Classification;

use Chess\FenToBoardFactory;
use Chess\Heuristics\FenHeuristics;
use Chess\ML\Supervised\Classification\CountLabeller;
use Chess\Tests\AbstractUnitTestCase;

class CountLabellerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start_labelled()
    {
        $fen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics($board))->getBalance();

        $label = (new CountLabeller())->label($balance);

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function e4_labelled()
    {
        $fen = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq - 0 1';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics($board))->getBalance();

        $label = (new CountLabeller())->label($balance);

        $expected = [
            'w' => 2,
            'b' => 1,
        ];

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function e4_e5_labelled()
    {
        $fen = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6 0 2';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics($board))->getBalance();

        $label = (new CountLabeller())->label($balance);

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function e4_e5_Nf3_Nf6_labelled()
    {
        $fen = 'rnbqkb1r/pppp1ppp/5n2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 2 3';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics($board))->getBalance();

        $label = (new CountLabeller())->label($balance);

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function A59_labelled()
    {
        $fen = 'rn1qkb1r/4pp1p/3p1np1/2pP4/4P3/2N3P1/PP3P1P/R1BQ1KNR b kq - 0 9';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics($board))->getBalance();

        $label = (new CountLabeller())->label($balance);

        $expected = [
            'w' => 6,
            'b' => 4,
        ];

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $fen = 'r1bqkb1r/pppp1Qpp/2n2n2/4p3/2B1P3/8/PPPP1PPP/RNB1K1NR b KQkq -';

        $board = FenToBoardFactory::create($fen);

        $balance = (new FenHeuristics($board))->getBalance();

        $label = (new CountLabeller())->label($balance);

        $expected = [
            'w' => 7,
            'b' => 3,
        ];

        $this->assertEquals($expected, $label);
    }
}
