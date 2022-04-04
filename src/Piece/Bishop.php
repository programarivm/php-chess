<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;
use Chess\Piece\AbstractPiece;

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
        parent::__construct($color, $sq, Symbol::BISHOP);

        $this->scope = (object)[
            'upLeft' => [],
            'upRight' => [],
            'bottomLeft' => [],
            'bottomRight' => []
        ];

        $this->scope();
    }

    /**
     * Calculates the bishop's scope.
     */
    protected function scope(): void
    {
        // top left diagonal
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] + 1;
            while (Validate::sq($file.$rank)) {
                $this->scope->upLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // top right diagonal
        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] + 1;
            while (Validate::sq($file.$rank)) {
                $this->scope->upRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // bottom left diagonal
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] - 1;
            while (Validate::sq($file.$rank))
            {
                $this->scope->bottomLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // bottom right diagonal
        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] - 1;
            while (Validate::sq($file.$rank))
            {
                $this->scope->bottomRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }
    }
}
