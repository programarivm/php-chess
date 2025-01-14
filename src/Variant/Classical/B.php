<?php

namespace Chess\Variant\Classical;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class B extends AbstractLinePiece
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::B);

        $this->flow = [
            0 => [],
            1 => [],
            2 => [],
            3 => [],
        ];

        $file = ord($this->file()) - 1;
        $rank = $this->rank() + 1;
        while ($file >= 97 && $rank <= $square::SIZE['ranks']) {
            $this->flow[0][] = chr($file) . $rank;
            $file -= 1;
            $rank += 1;
        }

        $file = ord($this->file()) + 1;
        $rank = $this->rank() + 1;
        while ($file <= 97 + $square::SIZE['files'] - 1 && $rank <= $square::SIZE['ranks']) {
            $this->flow[1][]  = chr($file) . $rank;
            $file += 1;
            $rank += 1;
        }

        $file = ord($this->file()) - 1;
        $rank = $this->rank() - 1;
        while ($file >= 97 && $rank >= 1) {
            $this->flow[2][] = chr($file) . $rank;
            $file -= 1;
            $rank -= 1;
        }

        $file = ord($this->file()) + 1;
        $rank = $this->rank() - 1;
        while ($file <= 97 + $square::SIZE['files'] - 1 && $rank >= 1) {
            $this->flow[3][] = chr($file) . $rank;
            $file += 1;
            $rank -= 1;
        }
    }
}
