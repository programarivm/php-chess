<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomStartPiecesTrait;
use Chess\Variant\Classical\PGN\Square;

class StartPieces
{
    use RandomStartPiecesTrait;

    public function __construct(array $startPos)
    {
        $this->namespace = 'Classical';

        $this->startPos = $startPos;

        $this->square = new Square();
    }
}
