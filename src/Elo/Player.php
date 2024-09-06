<?php

namespace Chess\Elo;

class Player
{
    private float $rating;

    public function __construct(float $rating)
    {
        $this->rating = $rating;
    }

    public function setRating($rating): Player
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRating(): float
    {
        return $this->rating;
    }
}
