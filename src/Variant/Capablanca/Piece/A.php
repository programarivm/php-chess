<?php

namespace Chess\Variant\Capablanca\Piece;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\Piece\B;
use Chess\Variant\Classical\Piece\N;

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

    public function lineOfAttack(): array
    {
        return [];
    }
}
