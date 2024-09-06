<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Elo\Player;
use Chess\Tests\AbstractUnitTestCase;

class PlayerTest extends AbstractUnitTestCase
{
    /**
    * @test
    */
    public function get_rating()
    {
        $player = new Player(1400);

        $this->assertEquals(1400, $player->getRating());
    }
}
