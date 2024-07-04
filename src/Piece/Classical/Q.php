<?php

namespace Chess\Piece\Classical;

use Chess\Piece\AbstractSlider;
use Chess\Piece\RType;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class Q extends AbstractSlider
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::Q);

        $this->mobility = [
            ...(new R($color, $sq, $square, RType::SLIDER))->mobility,
            ...(new B($color, $sq, $square))->mobility,
        ];
    }
}
