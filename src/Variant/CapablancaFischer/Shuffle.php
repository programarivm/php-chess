<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomShuffleTrait;
use Chess\Variant\Capablanca\PGN\Piece;

class Shuffle
{
    use RandomShuffleTrait;

    public function __construct()
    {
        $this->default =  [
            Piece::R,
            Piece::N,
            Piece::A,
            Piece::B,
            Piece::Q,
            Piece::K,
            Piece::B,
            Piece::C,
            Piece::N,
            Piece::R,
        ];
    }
}
