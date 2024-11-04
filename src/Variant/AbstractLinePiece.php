<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Square;

/**
 * Abstract Line Piece
 *
 * A piece that can move along straight lines of squares.
 */
abstract class AbstractLinePiece extends AbstractPiece
{
    /**
     * @param string $color
     * @param string $sq
     * @param \Chess\Variant\Classical\PGN\AN\Square $square
     * @param string $id
     */
    public function __construct(string $color, string $sq, Square $square, string $id)
    {
        parent::__construct($color, $sq, $square, $id);
    }

    /**
     * Returns an array representing the squares this piece can move to.
     *
     * @return array
     */
    public function moveSqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $key => $val) {
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
        foreach ($this->mobility as $key => $val) {
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
