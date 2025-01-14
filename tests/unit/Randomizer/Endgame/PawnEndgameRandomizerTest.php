<?php

namespace Chess\Tests\Unit\Randomizer\Endgame;

use Chess\Randomizer\Endgame\PawnEndgameRandomizer;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Color;

class PawnEndgameRandomizerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function w_get_board()
    {
        $board = (new PawnEndgameRandomizer($turn = Color::W))->board;

        $this->assertNotEmpty($board->toFen());
    }

    /**
     * @test
     */
    public function b_get_board()
    {
        $board = (new PawnEndgameRandomizer($turn = Color::B))->board;

        $this->assertNotEmpty($board->toFen());
    }
}
