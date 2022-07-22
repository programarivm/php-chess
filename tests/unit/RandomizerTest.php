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
        $board = (new Randomizer())->kings()->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }

    /**
     * @test
     */
    public function pieces()
    {
        $pieces = [
            Color::W => ['R'],
        ];

        $board = (new Randomizer())
            ->kings()
            ->pieces($pieces)
            ->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }
}
