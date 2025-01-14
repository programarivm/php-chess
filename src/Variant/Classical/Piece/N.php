<?php

namespace Chess\Variant\Classical\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class N extends AbstractPiece
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::N);

        $this->mobility = [];

        try {
            $file = chr(ord($this->file()) - 1);
            $rank = $this->rank() + 2;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->file()) - 2);
            $rank = $this->rank() + 1;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->file()) - 2);
            $rank = $this->rank() - 1;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->file()) - 1);
            $rank = $this->rank() - 2;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->file()) + 1);
            $rank = $this->rank() - 2;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->file()) + 2);
            $rank = $this->rank() - 1;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->file()) + 2);
            $rank = $this->rank() + 1;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->file()) + 1);
            $rank = $this->rank() + 2;
            if ($square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }
    }

    public function moveSqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->sqCount['free'])) {
                $sqs[] = $sq;
            } elseif (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    public function defendedSqs(): array
    {
        $sqs = [];
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    public function lineOfAttack(): array
    {
        return [];
    }
}
