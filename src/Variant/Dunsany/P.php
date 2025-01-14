<?php

namespace Chess\Variant\Dunsany;

use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\P as ClassicalP;

class P extends ClassicalP
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square);

        // two square advance
        if ($this->color === Color::W) {
            unset($this->flow[1]);
        }
    }
}
