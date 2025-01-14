<?php

namespace Chess\Tests\Unit\Randomizer\Checkmate;

use Chess\Randomizer\Checkmate\TwoBishopsRandomizer;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\Color;

class TwoBishopsRandomizerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function w_get_board()
    {
        $board = (new TwoBishopsRandomizer($turn = Color::W))->board;

        $this->assertNotEmpty($board->toFen());
    }

    /**
     * @test
     */
    public function b_get_board()
    {
        $board = (new TwoBishopsRandomizer($turn = Color::B))->board;

        $this->assertNotEmpty($board->toFen());
    }
}
