<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomStartPieces;
use Chess\Variant\Capablanca\PGN\AN\Square;

class StartPieces extends RandomStartPieces
{
    public function __construct(array $startPos)
    {
        $this->startPos = $startPos;

        $this->size = Square::SIZE;
    }
}
