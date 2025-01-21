<?php

namespace Chess\Variant\Capablanca;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Capablanca\PGN\Piece;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\N;

class A extends AbstractLinePiece
{
    use CapablancaTrait;

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::A);

        $this->flow = [
            ...(new B($color, $sq, $square))->flow,
            (new N($color, $sq, $square))->flow,
        ];
    }
}
