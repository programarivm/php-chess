<?php

namespace Chess\Variant\Classical;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class R extends AbstractLinePiece
{
    public string $type;

    public function __construct(string $color, string $sq, Square $square, string $type)
    {
        parent::__construct($color, $sq, Piece::R);

        $this->type = $type;

        $this->flow = [
            0 => [],
            1 => [],
            2 => [],
            3 => [],
        ];

        for ($i = $this->rank() + 1; $i <= $square::SIZE['ranks']; $i++) {
            $this->flow[0][] = $this->file() . $i;
        }

        for ($i = $this->rank() - 1; $i >= 1; $i--) {
            $this->flow[1][] = $this->file() . $i;
        }

        for ($i = ord($this->file()) - 1; $i >= 97; $i--) {
            $this->flow[2][] = chr($i) . $this->rank();
        }

        for ($i = ord($this->file()) + 1; $i <= 97 + $square::SIZE['files'] - 1; $i++) {
            $this->flow[3][] = chr($i) . $this->rank();
        }
    }
}
