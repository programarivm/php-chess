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
        $game->setK(32);
        $game->setScore(1, 0)->count();

        $this->assertGreaterThan(1400, $w->getRating());
        $this->assertLessThan(1400, $b->getRating());
    }
}
