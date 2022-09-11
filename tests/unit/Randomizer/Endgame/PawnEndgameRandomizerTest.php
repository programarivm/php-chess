<?php

namespace Chess\Tests\Unit\Randomizer\Endgame;

use Chess\Randomizer\Endgame\PawnEndgameRandomizer;
use Chess\FEN\BoardToStr;
use Chess\PGN\AN\Color;
use Chess\Tests\AbstractUnitTestCase;

class PawnEndgameRandomizerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function w_get_board()
    {
        $board = (new PawnEndgameRandomizer($turn = Color::W))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }

    /**
     * @test
     */
    public function b_get_board()
    {
        $board = (new PawnEndgameRandomizer($turn = Color::B))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }
}
