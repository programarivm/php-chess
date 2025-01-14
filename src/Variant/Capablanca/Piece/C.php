<?php

namespace Chess\Variant\Capablanca\Piece;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\RType;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\R;

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

    public function lineOfAttack(): array
    {
        return [];
    }
}
