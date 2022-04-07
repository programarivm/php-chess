<?php

namespace Chess\Tests\Unit;

use Chess\Game;
use Chess\Tests\AbstractUnitTestCase;

class GameTest extends AbstractUnitTestCase
{
    /*
    |--------------------------------------------------------------------------
    | heuristics()
    |--------------------------------------------------------------------------
    |
    | A chess game can be thought of in terms of snapshots describing what's
    | going on the board as reported by a number of evaluation features, thus,
    | chess positions can be evaluated considering the heuristics of the game.
    |
    */

    /**
     * @test
     */
    public function heuristics_e4_e5()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');
        $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w');

        $expected = [
            'w' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
            'b' => [
                [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
            ],
        ];

        $picture = $game->heuristics();

        $this->assertSame($expected, $picture);
    }

    /**
     * @test
     */
    public function heuristics_balance_e4_e5()
    {
        $game = new Game();
        $game->playFen('rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b');
        $game->playFen('rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w');

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $balance = $game->heuristics(true);

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function heuristics_balance_kaufman_01()
    {
        $fen = '1rbq1rk1/p1b1nppp/1p2p3/8/1B1pN3/P2B4/1P3PPP/2RQ1R1K w - - bm Nf6+';

        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen($fen);
        $game->play('w', 'Nf6');
        $game->play('b', 'gxf6');

        $expected = [
            [ -1, -1, -1, 1, 1, 0, 0, 0, 1, -1, 1, 0, 0, 1, 0, 0, -1, 0, 0 ],
        ];

        $balance = $game->heuristics(true, $fen);

        $this->assertEquals($expected, $balance);
    }

    /**
     * @test
     */
    public function heuristics_balance_e4_e5_f4_f5_Nc3_Nc6()
    {
        $fen = 'r1bqkbnr/pppp2pp/2n5/4pp2/4PP2/2N5/PPPP2PP/R1BQKBNR w KQkq - 2 4';

        $game = new Game(Game::MODE_LOAD_FEN);
        $game->loadFen($fen);

        $expected = [
            [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $balance = $game->heuristics(true, $fen);

        $this->assertSame($expected, $balance);
    }
}
