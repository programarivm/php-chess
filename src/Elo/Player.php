<?php

namespace Chess\Elo;

class Player
{
    private int $rating;

    public function __construct(int $rating)
    {
        $this->rating = $rating;
    }

    public function setRating(int $rating): Player
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
}
