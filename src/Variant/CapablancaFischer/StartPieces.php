<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomStartPiecesTrait;
use Chess\Variant\Capablanca\PGN\Square;

class StartPieces
{
    use RandomStartPiecesTrait;

    public function __construct(array $shuffle)
    {
        $this->namespace = 'Capablanca';

        $this->shuffle = $shuffle;

        $this->square = new Square();
    }
}
