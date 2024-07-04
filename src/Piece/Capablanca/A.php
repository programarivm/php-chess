<?php

namespace Chess\Piece\Capablanca;

use Chess\Piece\AbstractPiece;
use Chess\Piece\Classical\B;
use Chess\Piece\Classical\N;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;

class A extends AbstractPiece
{
    use CapablancaTrait;

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::A);

        $this->mobility = [
            ...(new B($color, $sq, $square))->mobility,
            'knight' => (new N($color, $sq, $square))->mobility,
        ];
    }
}
