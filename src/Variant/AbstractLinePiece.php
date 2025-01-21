<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\Piece;

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
        foreach ($this->flow as $val) {
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
        foreach ($this->flow as $val) {
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
}
