<?php

namespace Chess\Elo;

use Closure;

class Game
{
    private Player $w;

    private Player $b;

    private float $score1;

    private float $score2;

    private float $k;

    private $goalIndexHandler;

    private $homeCorrectionHandler;

    private int $home;

    const WIN = 1;
    const DRAW = 0.5;
    const LOSS = 0;

    public function __construct(Player $w, Player $b)
    {
        $this->w = $w;
        $this->b = $b;
    }

    public function setScore(float $score1, float $score2): Game
    {
        $this->score1 = $score1;
        $this->score2 = $score2;

        return $this;
    }

    public function setK(float $k): Game
    {
        $this->k = $k;

        return $this;
    }

    public function count(): void
    {
        $rating1 = $this->w->getRating() + $this->k * $this->getGoalIndex() * ($this->getMatchScore() - $this->getExpectedScore());
        $rating2 = $this->w->getRating() + $this->b->getRating() - $rating1;
        $this->w->setRating($rating1);
        $this->b->setRating($rating2);
    }

    public function getW(): Player
    {
        return $this->w;
    }

    public function getB(): Player
    {
        return $this->b;
    }

    private function getMatchScore(): float
    {
        $diff = $this->score1 - $this->score2;
        if ($diff < 0) {
            return static::LOSS;
        } elseif ($diff > 0) {
            return static::WIN;
        }

        return static::DRAW;
    }

    private function getExpectedScore(): float
    {
        $diff = $this->b->getRating() - $this->w->getRating();
        $diff = $this->getHomeCorrection($diff);

        return 1 / (1 + pow(10, ($diff / 400)));
    }

    public function setGoalIndexHandler(Closure $handler): Game
    {
        $this->goalIndexHandler = $handler;

        return $this;
    }

    private function getGoalIndex()
    {
        if (is_callable($this->goalIndexHandler)) {
            return call_user_func($this->goalIndexHandler, $this->score1, $this->score2);
        }

        return 1;
    }

    public function setHomeCorrectionHandler(Closure $handler): Game
    {
        $this->homeCorrectionHandler = $handler;

        return $this;
    }

    private function getHomeCorrection($diff)
    {
        if (is_callable($this->homeCorrectionHandler)) {
            return call_user_func($this->homeCorrectionHandler, $this->home, $diff);
        }

        return $diff;
    }

    public function setHome(Player $player): Game
    {
        $this->home = $player;

        return $this;
    }
}
