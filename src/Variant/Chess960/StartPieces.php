<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomStartPiecesTrait;
use Chess\Variant\Classical\PGN\AN\Square;

class StartPieces
{
    use RandomStartPiecesTrait;
    
    public function __construct(array $startPos)
    {
        $this->startPos = $startPos;

        $this->size = Square::SIZE;
    }
}
