<?php

namespace Chess\Tests\Unit\Randomizer;

use Chess\Randomizer\Randomizer;
use Chess\FEN\BoardToStr;
use Chess\PGN\AN\Color;
use Chess\Tests\AbstractUnitTestCase;

class RandomizerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function kings()
    {
        $turn = Color::W;

        $board = (new Randomizer($turn))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }

    /**
     * @test
     */
    public function w_N_B_R()
    {
        $turn = Color::W;

        $items = [
            Color::W => ['N', 'B', 'R'],
        ];

        $board = (new Randomizer($turn, $items))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }

    /**
     * @test
     */
    public function b_N_B_R()
    {
        $turn = Color::B;

        $items = [
            Color::B => ['N', 'B', 'R'],
        ];

        $board = (new Randomizer($turn, $items))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }
}
