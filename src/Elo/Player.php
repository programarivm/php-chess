<?php

namespace Chess\Elo;

class Player
{
    private $rating;

    public function __construct($rating)
    {
        $this->rating = $rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function getRating()
    {
        return $this->rating;
    }
}
