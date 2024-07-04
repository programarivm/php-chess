<?php

namespace Chess\Piece\Classical;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\AbstractSlider;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class B extends AbstractSlider
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::B);

        $this->mobility = [
            'upLeft' => [],
            'upRight' => [],
            'downLeft' => [],
            'downRight' => []
        ];

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() + 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility['upLeft'][] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() + 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility['upRight'][]  = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() - 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility['downLeft'][] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() - 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility['downRight'][] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {
        }
    }
}
