<?php

namespace Chess\Tests\Unit;

use Chess\Randomizer;
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
    public function pieces()
    {
        $turn = Color::W;

        $items = [
            Color::W => ['N', 'B', 'R'],
        ];

        $board = (new Randomizer($turn, $items))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }
}
