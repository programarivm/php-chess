<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomStartPieces;
use Chess\Variant\Classical\PGN\AN\Square;

class StartPieces extends RandomStartPieces
{
    public function __construct(array $startPos)
    {
        $this->startPos = $startPos;

        $this->size = Square::SIZE;
    }
}
