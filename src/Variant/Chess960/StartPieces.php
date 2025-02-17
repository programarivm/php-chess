<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomStartPiecesTrait;
use Chess\Variant\Classical\PGN\Square;

class StartPieces
{
    use RandomStartPiecesTrait;

    public function __construct(array $shuffle)
    {
        $this->namespace = 'Classical';

        $this->shuffle = $shuffle;

        $this->square = new Square();
    }
}
