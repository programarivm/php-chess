<?php

namespace Chess\Piece;

trait CapablancaTrait
{
    /**
     * Returns the piece's legal moves.
     *
     * @return array
     */
    public function sqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $key => $val) {
            if ($key !== 'knight') {
                foreach ($val as $sq) {
                    if (
                        !in_array($sq, $this->board->sqCount->used->{$this->color}) &&
                        !in_array($sq, $this->board->sqCount->used->{$this->oppColor()})
                    ) {
                        $sqs[] = $sq;
                    } elseif (in_array($sq, $this->board->sqCount->used->{$this->oppColor()})) {
                        $sqs[] = $sq;
                        break 1;
                    } elseif (in_array($sq, $this->board->sqCount->used->{$this->color})) {
                        break 1;
                    }
                }
            } else {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->sqCount->free)) {
                        $sqs[] = $sq;
                    } elseif (in_array($sq, $this->board->sqCount->used->{$this->oppColor()})) {
                        $sqs[] = $sq;
                    }
                }
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
        foreach ($this->mobility as $key => $val) {
            if ($key !== 'knight') {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->sqCount->used->{$this->color})) {
                        $sqs[] = $sq;
                        break 1;
                    } elseif (in_array($sq, $this->board->sqCount->used->{$this->oppColor()})) {
                        break 1;
                    }
                }
            } else {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->sqCount->used->{$this->color})) {
                        $sqs[] = $sq;
                    }
                }
            }
        }

        return $sqs;
    }
}
