<?php

namespace Chess\Tests\Unit\Game;

use Chess\Game;
use Chess\Tests\AbstractUnitTestCase;

class HeuristicsTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5_playfen()
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
    public function e4_e5_playfen_balanced()
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
    public function kaufman_01_play_Nf6_gxf6()
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
    public function e4_e5_f4_f5_Nc3_Nc6()
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
