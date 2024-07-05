<?php

namespace Chess\Variant\Dunsany\Piece;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Piece\P as ClassicalP;

class P extends ClassicalP
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::P);

        // two square advance
        if ($this->color === Color::W) {
            unset($this->mobility[1]);
        }
    }
}
