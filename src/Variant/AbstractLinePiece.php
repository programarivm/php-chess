<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Square;

abstract class AbstractLinePiece extends AbstractPiece
{
    public function __construct(string $color, string $sq, Square $square, string $id)
    {
        parent::__construct($color, $sq, $square, $id);
    }

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

    public function defendedSqs(): ?array
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
