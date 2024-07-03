<?php

namespace Chess\Piece\Capablanca;

use Chess\Piece\AbstractPiece;
use Chess\Piece\N;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;

class C extends AbstractPiece
{
    use CapablancaTrait;

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::C);

        $this->mobility = [
            ...(new R($color, $sq, $square, RType::SLIDER))->mobility,
            'knight' => (new N($color, $sq, $square))->mobility,
        ];
    }
}
