<?php

namespace Chess\Variant\Classical;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\RType;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class Q extends AbstractLinePiece
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::Q);

        $this->flow = [
            ...(new R($color, $sq, $square, RType::R))->flow,
            ...(new B($color, $sq, $square))->flow,
        ];
    }
}
