<?php

namespace Chess\Elo;

use Closure;

class Game
{
    const WIN = 1;

    const DRAW = 0.5;

    const LOSS = 0;

    private Player $w;

    private Player $b;

    private float $wScore;

    private float $bScore;

    private float $k;

    private $goalIndexHandler;

    private $homeCorrectionHandler;

    private int $home;

    public function __construct(Player $w, Player $b)
    {
        $this->w = $w;
        $this->b = $b;
    }

    public function setScore(float $wScore, float $bScore): Game
    {
        $this->wScore = $wScore;
        $this->bScore = $bScore;

        return $this;
    }

    public function setK(float $k): Game
    {
        $this->k = $k;

        return $this;
    }

    public function count(): void
    {
        $wRating = $this->w->getRating() + $this->k * $this->getGoalIndex()
            * ($this->getMatchScore() - $this->getExpectedScore());
        $bRating = $this->w->getRating() + $this->b->getRating() - $wRating;
        $this->w->setRating($wRating);
        $this->b->setRating($bRating);
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
        $diff = $this->wScore - $this->bScore;
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
            return call_user_func($this->goalIndexHandler, $this->wScore, $this->bScore);
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
