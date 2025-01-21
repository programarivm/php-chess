<?php

namespace Chess\Variant\Capablanca;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\RType;
use Chess\Variant\Capablanca\PGN\Piece;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\N;
use Chess\Variant\Classical\R;

class C extends AbstractLinePiece
{
    use CapablancaTrait;

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::C);

        $this->flow = [
            ...(new R($color, $sq, $square, RType::R))->flow,
            (new N($color, $sq, $square))->flow,
        ];
    }
}
