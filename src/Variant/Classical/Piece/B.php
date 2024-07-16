<?php

namespace Chess\Variant\Classical\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractSlider;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class B extends AbstractSlider
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::B);

        $this->mobility = [
            0 => [],
            1 => [],
            2 => [],
            3 => [],
        ];

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() + 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility[0][] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() + 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility[1][]  = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() - 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility[2][] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() - 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility[3][] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {
        }
    }
}
