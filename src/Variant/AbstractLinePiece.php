<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Abstract Line Piece
 *
 * A piece that can move along straight lines of squares.
 */
abstract class AbstractLinePiece extends AbstractPiece
{
    /**
     * Returns an array representing the squares this piece can move to.
     *
     * @return array
     */
    public function moveSqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $val) {
            foreach ($val as $sq) {
                if (in_array($sq, $this->board->sqCount['free'])) {
                    $sqs[] = $sq;
                } elseif (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                    $sqs[] = $sq;
                    break 1;
                } else {
                    break 1;
                }
            }
        }

        return $sqs;
    }

    /**
     * Returns an array representing the defended squares by this piece.
     *
     * @return array
     */
    public function defendedSqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $val) {
            foreach ($val as $sq) {
                if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                    $sqs[] = $sq;
                    break 1;
                } elseif (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                    break 1;
                }
            }
        }

        return $sqs;
    }

    /**
     * Returns an array representing the line of attack of this piece.
     *
     * @return array
     */
    public function lineOfAttack(): array
    {
        $sqs = [];
        $king = $this->board->piece($this->oppColor(), Piece::K);
        if ($this->file() === $king->file()) {
            if ($this->rank() > $king->rank()) {
                for ($i = 1; $i < $this->rank() - $king->rank(); $i++) {
                    $sqs[] = $this->file() . ($king->rank() + $i);
                }
            } else {
                for ($i = 1; $i < $king->rank() - $this->rank(); $i++) {
                    $sqs[] = $this->file() . ($king->rank() - $i);
                }
            }
        } elseif ($this->rank() === $king->rank()) {
            if ($this->file() > $king->file()) {
                for ($i = 1; $i < ord($this->file()) - ord($king->file()); $i++) {
                    $sqs[] = chr(ord($king->file()) + $i) . $this->rank();
                }
            } else {
                for ($i = 1; $i < ord($king->file()) - ord($this->file()); $i++) {
                    $sqs[] = chr(ord($king->file()) - $i) . $this->rank();
                }
            }
        } elseif (abs(ord($this->file()) - ord($king->file())) ===
            abs(ord($this->rank()) - ord($king->rank()))
        ) {
            if ($this->file() > $king->file() && $this->rank() < $king->rank()) {
                for ($i = 1; $i < $king->rank() - $this->rank(); $i++) {
                    $sqs[] = chr(ord($king->file()) + $i) . ($king->rank() - $i);
                }
            } elseif ($this->file() < $king->file() && $this->rank() < $king->rank()) {
                for ($i = 1; $i < $king->rank() - $this->rank(); $i++) {
                    $sqs[] = chr(ord($king->file()) - $i) . ($king->rank() - $i);
                }
            } elseif ($this->file() < $king->file() && $this->rank() > $king->rank()) {
                for ($i = 1; $i < $this->rank() - $king->rank(); $i++) {
                    $sqs[] = chr(ord($king->file()) - $i) . ($king->rank() + $i);
                }
            } else {
                for ($i = 1; $i < $this->rank() - $king->rank(); $i++) {
                    $sqs[] = chr(ord($king->file()) + $i) . ($king->rank() + $i);
                }
            }
        }

        return $sqs;
    }
}
