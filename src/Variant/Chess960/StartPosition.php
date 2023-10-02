<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomStartPositionTrait;
use Chess\Variant\Classical\PGN\AN\Piece;

class StartPosition
{
    use RandomStartPositionTrait;
    
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
