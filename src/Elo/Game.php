<?php

namespace Chess\Elo;

use Closure;

class Game
{
    private Player $player1;

    private Player $player2;

    private float $score1;

    private float $score2;

    private float $k;

    private $goalIndexHandler;

    private $homeCorrectionHandler;

    private int $home;

    const WIN = 1;
    const DRAW = 0.5;
    const LOSS = 0;

    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
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
        $rating1 = $this->player1->getRating() + $this->k * $this->getGoalIndex() * ($this->getMatchScore() - $this->getExpectedScore());
        $rating2 = $this->player1->getRating() + $this->player2->getRating() - $rating1;
        $this->player1->setRating($rating1);
        $this->player2->setRating($rating2);
    }

    public function getPlayer1(): Player
    {
        return $this->player1;
    }

    public function getPlayer2(): Player
    {
        return $this->player2;
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
        $diff = $this->player2->getRating() - $this->player1->getRating();
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
