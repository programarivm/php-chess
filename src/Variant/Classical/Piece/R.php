<?php

namespace Chess\Variant\Classical\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractSlider;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class R extends AbstractSlider
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

        try {
            $file = $this->sq[0];
            $rank = $this->rank() + 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility[0][] = $file . $rank;
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = $this->sq[0];
            $rank = $this->rank() - 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility[1][] = $file . $rank;
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank();
            while ($this->square->validate($file . $rank)) {
                $this->mobility[2][] = $file . $rank;
                $file = chr(ord($file) - 1);
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank();
            while ($this->square->validate($file . $rank)) {
                $this->mobility[3][] = $file . $rank;
                $file = chr(ord($file) + 1);
            }
        } catch (UnknownNotationException $e) {
        }
    }
}
