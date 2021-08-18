<?php

namespace Chess\Tests\Unit\Game;

use Chess\Game;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;

class HeuristicPictureTest extends AbstractUnitTestCase
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
            Symbol::WHITE => [
                [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            ],
            Symbol::BLACK => [
                [ 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 0.5 ],
            ],
        ];

        $picture = $game->heuristicPicture();

        $this->assertEquals($expected, $picture);
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
            [ 0, 0, 0, 0, 0, 0, 0, 0 ],
        ];

        $balance = $game->heuristicPicture(true);

        $this->assertEquals($expected, $balance);
    }
}
