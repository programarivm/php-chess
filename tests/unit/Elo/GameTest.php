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
    public function get_rating_1400_1400_1_0()
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

    /**
     * @test
     */
    public function get_rating_1500_1500_1_0()
    {
        $w = new Player(1500);
        $b = new Player(1500);

        $game =  new Game($w, $b);
        $game->setK(32)
            ->setScore(1, 0)
            ->count();

        $this->assertEquals(1516, $w->getRating());
        $this->assertEquals(1484, $b->getRating());
    }

    /**
     * @test
     */
    public function get_rating_1500_2000_1_0()
    {
        $w = new Player(1500);
        $b = new Player(2000);

        $game =  new Game($w, $b);
        $game->setK(32)
            ->setScore(1, 0)
            ->count();

        $this->assertEquals(1530, $w->getRating());
        $this->assertEquals(1969, $b->getRating());
    }

    /**
     * @test
     */
    public function get_rating_1500_2000_0_0()
    {
        $w = new Player(1500);
        $b = new Player(2000);

        $game =  new Game($w, $b);
        $game->setK(32)
            ->setScore(0, 0)
            ->count();

        $this->assertEquals(1514, $w->getRating());
        $this->assertEquals(1985, $b->getRating());
    }

    /**
     * @test
     */
    public function get_rating_1500_2000_1_1()
    {
        $w = new Player(1500);
        $b = new Player(2000);

        $game =  new Game($w, $b);
        $game->setK(32)
            ->setScore(1, 1)
            ->count();

        $this->assertEquals(1514, $w->getRating());
        $this->assertEquals(1985, $b->getRating());
    }

    /**
     * @test
     */
    public function get_rating_1500_2000_0_1()
    {
        $w = new Player(1500);
        $b = new Player(2000);

        $game =  new Game($w, $b);
        $game->setK(32)
            ->setScore(0, 1)
            ->count();

        $this->assertEquals(1498, $w->getRating());
        $this->assertEquals(2001, $b->getRating());
    }
}
