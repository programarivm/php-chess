<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomPosition;
use Chess\Variant\Capablanca\PGN\AN\Piece;

class StartPosition extends RandomPosition
{
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
