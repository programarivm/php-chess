<?php

namespace Chess\Variant\Capablanca\Piece;

use Chess\Variant\AbstractPiece;
use Chess\Variant\RType;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\R;

class C extends AbstractPiece
{
    use CapablancaTrait;

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::C);

        $this->mobility = [
            ...(new R($color, $sq, $square, RType::R))->mobility,
            (new N($color, $sq, $square))->mobility,
        ];
    }
}
