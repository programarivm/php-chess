<?php

namespace Chess\Tests\Unit\Randomizer\Checkmate;

use Chess\Randomizer\Checkmate\TwoBishopsRandomizer;
use Chess\FEN\BoardToStr;
use Chess\PGN\AN\Color;
use Chess\Tests\AbstractUnitTestCase;

class TwoBishopsRandomizerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function w_get_board()
    {
        $board = (new TwoBishopsRandomizer($turn = Color::W))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }

    /**
     * @test
     */
    public function b_get_board()
    {
        $board = (new TwoBishopsRandomizer($turn = Color::B))->getBoard();

        $fen = (new BoardToStr($board))->create();

        $this->assertNotEmpty($fen);
    }
}
