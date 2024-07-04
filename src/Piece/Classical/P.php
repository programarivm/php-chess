<?php

namespace Chess\Piece\Classical;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class P extends AbstractPiece
{
    public array $ranks;

    public array $captureSqs;

    public string $enPassantSq = '';

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::P);

        if ($this->color === Color::W) {
            $this->ranks = [
                'start' => 2,
                'next' => $this->rank() + 1,
                'end' => $this->square::SIZE['ranks'],
            ];
        } elseif ($this->color === Color::B) {
            $this->ranks = [
                'start' => $this->square::SIZE['ranks'] - 1,
                'next' => $this->rank() - 1,
                'end' => 1,
            ];
        }

        $this->captureSqs = [];

        $this->mobility = [];

        // next rank
        try {
            if ($this->square->validate($this->file() . $this->ranks['next'])) {
                $this->mobility[] = $this->file() . $this->ranks['next'];
            }
        } catch (UnknownNotationException $e) {

        }

        // two square advance
        if ($this->rank() === 2 && $this->ranks['start'] == 2) {
            $this->mobility[] = $this->file() . ($this->ranks['start'] + 2);
        } elseif (
            $this->rank() === $this->square::SIZE['ranks'] - 1 &&
            $this->ranks['start'] == $this->square::SIZE['ranks'] - 1
        ) {
            $this->mobility[] = $this->file() . ($this->ranks['start'] - 2);
        }

        // capture square
        try {
            $file = chr(ord($this->file()) - 1);
            if ($this->square->validate($file . $this->ranks['next'])) {
                $this->captureSqs[] = $file . $this->ranks['next'];
            }
        } catch (UnknownNotationException $e) {

        }

        // capture square
        try {
            $file = chr(ord($this->file()) + 1);
            if ($this->square->validate($file . $this->ranks['next'])) {
                $this->captureSqs[] = $file . $this->ranks['next'];
            }
        } catch (UnknownNotationException $e) {

        }
    }

    public function legalSqs(): array
    {
        $sqs = [];

        // mobility squares
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->sqCount['free'])) {
                $sqs[] = $sq;
            } else {
                break;
            }
        }

        // capture squares
        foreach ($this->captureSqs as $sq) {
            if (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                $sqs[] = $sq;
            }
        }

        // en passant square
        $history = $this->board->history;
        $end = end($history);
        if ($end && $end['move']['id'] === Piece::P && $end['move']['color'] === $this->oppColor()) {
           if ($this->color === Color::W) {
               if ($this->rank() === $this->square::SIZE['ranks'] - 3) {
                   $captureSq = $end['move']['sq']['next'][0] . ($this->rank() + 1);
                   if (in_array($captureSq, $this->captureSqs)) {
                        $this->enPassantSq = $captureSq;
                        $sqs[] = $captureSq;
                   }
               }
           } elseif ($this->color === Color::B) {
               if ($this->rank() === 4) {
                   $captureSq = $end['move']['sq']['next'][0] . ($this->rank() - 1);
                   if (in_array($captureSq, $this->captureSqs)) {
                       $this->enPassantSq = $captureSq;
                       $sqs[] = $captureSq;
                   }
               }
           }
        } else {
           $sqs[] = $this->enPassantSq;
        }

        return array_filter(array_unique($sqs));
    }

    public function defendedSqs(): ?array
    {
        $sqs = [];
        foreach($this->captureSqs as $sq) {
            if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    public function isPromoted(): bool
    {
        $rank = (int) substr($this->move['sq']['next'], 1);

        return isset($this->move['newId']) && $rank === $this->ranks['end'];
    }

    public function enPassantPawn(): ?P
    {
        if ($this->enPassantSq) {
            $rank = (int) substr($this->enPassantSq, 1);
            $this->color === Color::W ? $rank-- : $rank++;
            return $this->board->pieceBySq($this->enPassantSq[0] . $rank);
        }

        return null;
    }
}
