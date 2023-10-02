<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomStartPiecesTrait;
use Chess\Variant\Capablanca\PGN\AN\Square;

class StartPieces
{
    use RandomStartPiecesTrait;

    public function __construct(array $startPos)
    {
        $this->startPos = $startPos;

        $this->size = Square::SIZE;
    }
}
