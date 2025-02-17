<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomShuffleTrait;
use Chess\Variant\Classical\PGN\Piece;

class Shuffle
{
    use RandomShuffleTrait;
    
    public function __construct()
    {
        $this->default =  [
            Piece::R,
            Piece::N,
            Piece::B,
            Piece::Q,
            Piece::K,
            Piece::B,
            Piece::N,
            Piece::R,
        ];
    }
}
