<?php

namespace Chess\Variant\Classical\Piece;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class R extends AbstractLinePiece
{
    public string $type;

    public function __construct(string $color, string $sq, Square $square, string $type)
    {
        parent::__construct($color, $sq, $square, Piece::R);

        $this->type = $type;

        $this->mobility = [
            0 => [],
            1 => [],
            2 => [],
            3 => [],
        ];

        for ($i = $this->rank() + 1; $i <= $this->square::SIZE['ranks']; $i++) {
            $this->mobility[0][] = $this->file() . $i;
        }

        for ($i = $this->rank() - 1; $i >= 1; $i--) {
            $this->mobility[1][] = $this->file() . $i;
        }

        for ($i = ord($this->file()) - 1; $i >= ord('a'); $i--) {
            $this->mobility[2][] = chr($i) . $this->rank();
        }

        for ($i = ord($this->file()) + 1; $i <= ord('a') + $this->square::SIZE['ranks'] - 1; $i++) {
            $this->mobility[3][] = chr($i) . $this->rank();
        }
    }
}
