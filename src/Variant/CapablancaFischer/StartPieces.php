<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomStartPiecesTrait;
use Chess\Variant\VariantType;
use Chess\Variant\Capablanca\PGN\Square;

class StartPieces
{
    use RandomStartPiecesTrait;

    public function __construct(array $startPos)
    {
        $this->variant = VariantType::CAPABLANCA;

        $this->startPos = $startPos;

        $this->square = new Square();
    }
}
