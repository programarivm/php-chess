<?php

namespace Chess\Variant\Capablanca\Piece;

use Chess\Variant\Capablanca\PGN\AN\Piece;

trait CapablancaTrait
{
    public function moveSqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $key => $val) {
            if ($key !== 4) {
                foreach ($val as $sq) {
                    if (
                        !in_array($sq, $this->board->sqCount['used'][$this->color]) &&
                        !in_array($sq, $this->board->sqCount['used'][$this->oppColor()])
                    ) {
                        $sqs[] = $sq;
                    } elseif (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                        $sqs[] = $sq;
                        break 1;
                    } elseif (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                        break 1;
                    }
                }
            } else {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->sqCount['free'])) {
                        $sqs[] = $sq;
                    } elseif (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                        $sqs[] = $sq;
                    }
                }
            }
        }

        return $sqs;
    }

    public function defendedSqs(): ?array
    {
        $sqs = [];
        foreach ($this->mobility as $key => $val) {
            if ($key !== 4) {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                        $sqs[] = $sq;
                        break 1;
                    } elseif (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                        break 1;
                    }
                }
            } else {
                foreach ($val as $sq) {
                    if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                        $sqs[] = $sq;
                    }
                }
            }
        }

        return $sqs;
    }
}
