<?php

namespace Chess\Variant\Capablanca\Piece;

use Chess\Variant\AbstractPiece;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\Piece\B;
use Chess\Variant\Classical\Piece\N;

class A extends AbstractPiece
{
    use CapablancaTrait;

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::A);

        $this->mobility = [
            ...(new B($color, $sq, $square))->mobility,
            (new N($color, $sq, $square))->mobility,
        ];
    }
}
