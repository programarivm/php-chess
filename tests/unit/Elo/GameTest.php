<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Elo\Game;
use Chess\Elo\Player;
use Chess\Tests\AbstractUnitTestCase;

class GameTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_rating()
    {
        $w = new Player(1400);
        $b = new Player(1400);

        $game =  new Game($w, $b);
        $game->setK(32)
            ->setScore(1, 0)
            ->count();

        $this->assertEquals(1416, $w->getRating());
        $this->assertEquals(1384, $b->getRating());
    }
}
