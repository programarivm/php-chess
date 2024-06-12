<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

/**
 * Knight.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class N extends AbstractPiece
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param Square \Chess\Variant\Classical\PGN\AN\Square $square
     */
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::N);

        $this->mobility = [];

        $this->mobility();
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(): AbstractPiece
    {
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() + 2;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 2);
            $rank = $this->rank() + 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 2);
            $rank = $this->rank() - 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() - 2;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() - 2;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 2);
            $rank = $this->rank() - 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 2);
            $rank = $this->rank() + 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() + 2;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        return $this;
    }

    /**
     * Returns the piece's legal moves.
     *
     * @return array
     */
    public function sqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->sqCount->free)) {
                $sqs[] = $sq;
            } elseif (in_array($sq, $this->board->sqCount->used->{$this->oppColor()})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    /**
     * Returns the squares defended by the piece.
     *
     * @return array|null
     */
    public function defendedSqs(): ?array
    {
        $sqs = [];
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->sqCount->used->{$this->color})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }
}
