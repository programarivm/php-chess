<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;
use Chess\Piece\AbstractPiece;

/**
 * Knight class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Knight extends AbstractPiece
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct(string $color, string $square)
    {
        parent::__construct($color, $square, Symbol::KNIGHT);

        $this->scope = (object)[
            'jumps' => []
        ];

        $this->scope();
    }

    /**
     * Calculates the knight's scope.
     */
    protected function scope(): void
    {
        try {
            $file = chr(ord($this->position[0]) - 1);
            $rank = (int)$this->position[1] + 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) - 2);
            $rank = (int)$this->position[1] + 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) - 2);
            $rank = (int)$this->position[1] - 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) - 1);
            $rank = (int)$this->position[1] - 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) + 1);
            $rank = (int)$this->position[1] - 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {

            $file = chr(ord($this->position[0]) + 2);
            $rank = (int)$this->position[1] - 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) + 2);
            $rank = (int)$this->position[1] + 1;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->position[0]) + 1);
            $rank = (int)$this->position[1] + 2;
            if (Validate::square($file.$rank)) {
                $this->scope->jumps[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

    }

    public function getLegalMoves(): array
    {
        $moves = [];
        foreach ($this->scope->jumps as $square) {
            if (in_array($square, $this->boardStatus->squares->free)) {
                $moves[] = $square;
            } elseif (in_array($square, $this->boardStatus->squares->used->{$this->getOppColor()})) {
                $moves[] = $square;
            }
        }

        return $moves;
    }

    public function getDefendedSquares(): array
    {
        $squares = [];
        foreach ($this->scope->jumps as $square) {
            if (in_array($square, $this->boardStatus->squares->used->{$this->getColor()})) {
                $squares[] = $square;
            }
        }

        return $squares;
    }
}
