<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\AN\Square;
use Chess\PGN\AN\Piece;

/**
 * Bishop class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Bishop extends Slider
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     */
    public function __construct(string $color, string $sq)
    {
        parent::__construct($color, $sq, Piece::B);

        $this->travel = (object)[
            'upLeft' => [],
            'upRight' => [],
            'bottomLeft' => [],
            'bottomRight' => []
        ];

        $this->setTravel();
    }

    /**
     * Calculates the bishop's travel.
     */
    protected function setTravel(): void
    {
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] + 1;
            while (Square::validate($file.$rank)) {
                $this->travel->upLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] + 1;
            while (Square::validate($file.$rank)) {
                $this->travel->upRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] - 1;
            while (Square::validate($file.$rank))
            {
                $this->travel->bottomLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] - 1;
            while (Square::validate($file.$rank))
            {
                $this->travel->bottomRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }
    }
}
