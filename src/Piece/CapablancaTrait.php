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
                        !in_array($sq, $this->board->getSqEval()->used->{$this->getColor()}) &&
                        !in_array($sq, $this->board->getSqEval()->used->{$this->oppColor()})
                    ) {
                        $sqs[] = $sq;
                    } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->oppColor()})) {
                        $sqs[] = $sq;
                        break 1;
                    } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                        break 1;
                    }
                }
            } else {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->getSqEval()->free)) {
                        $sqs[] = $sq;
                    } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->oppColor()})) {
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
                    if (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                        $sqs[] = $sq;
                        break 1;
                    } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->oppColor()})) {
                        break 1;
                    }
                }
            } else {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                        $sqs[] = $sq;
                    }
                }
            }
        }

        return $sqs;
    }
}
