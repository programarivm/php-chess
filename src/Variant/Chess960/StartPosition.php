<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomPosition;
use Chess\Variant\Classical\PGN\AN\Piece;

class StartPosition extends RandomPosition
{
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
