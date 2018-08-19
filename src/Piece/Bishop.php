<?php

namespace PGNChess\Piece;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChess\Piece\AbstractPiece;

/**
 * Bishop class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Bishop extends Slider
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct($color, $square)
    {
        parent::__construct($color, $square, Symbol::BISHOP);

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
    protected function scope()
    {
        // top left diagonal
        try {
            $file = chr(ord($this->position[0]) - 1);
            $rank = (int)$this->position[1] + 1;
            while (PgnValidate::square($file.$rank)) {
                $this->scope->upLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // top right diagonal
        try {
            $file = chr(ord($this->position[0]) + 1);
            $rank = (int)$this->position[1] + 1;
            while (PgnValidate::square($file.$rank)) {
                $this->scope->upRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // bottom left diagonal
        try {
            $file = chr(ord($this->position[0]) - 1);
            $rank = (int)$this->position[1] - 1;
            while (PgnValidate::square($file.$rank))
            {
                $this->scope->bottomLeft[] = $file . $rank;
                $file = chr(ord($file) - 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // bottom right diagonal
        try {
            $file = chr(ord($this->position[0]) + 1);
            $rank = (int)$this->position[1] - 1;
            while (PgnValidate::square($file.$rank))
            {
                $this->scope->bottomRight[] = $file . $rank;
                $file = chr(ord($file) + 1);
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }
    }
}
