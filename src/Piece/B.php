<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Bishop.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class B extends Slider
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param array $size
     */
    public function __construct(string $color, string $sq, array $size)
    {
        parent::__construct($color, $sq, $size, Piece::B);

        $this->mobility = (object)[
            'upLeft' => [],
            'upRight' => [],
            'downLeft' => [],
            'downRight' => []
        ];

        $this->mobility();
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return AbstractPiece
     */
    protected function mobility(): AbstractPiece
    {
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)ltrim($this->sq, $this->sq[0]) + 1;
            while ($this->isValidSq($file.$rank)) {
                $this->mobility->upLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)ltrim($this->sq, $this->sq[0]) + 1;
            while ($this->isValidSq($file.$rank)) {
                $this->mobility->upRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)ltrim($this->sq, $this->sq[0]) - 1;
            while ($this->isValidSq($file.$rank))
            {
                $this->mobility->downLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)ltrim($this->sq, $this->sq[0]) - 1;
            while ($this->isValidSq($file.$rank))
            {
                $this->mobility->downRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException) {

        }

        return $this;
    }
}
