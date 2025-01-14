<?php

namespace Chess\Variant\Classical\Piece;

use Chess\Variant\AbstractPiece;
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
        parent::__construct($color, $sq, Piece::P);

        $this->ranks = $this->color === Color::W
            ? [
                'start' => 2,
                'next' => $this->rank() + 1,
                'end' => $square::SIZE['ranks'],
            ]
            : [
                'start' => $square::SIZE['ranks'] - 1,
                'next' => $this->rank() - 1,
                'end' => 1,
            ];

        $this->captureSqs = [];

        $this->flow = [];

        // next rank
        if ($this->ranks['next'] <= $square::SIZE['ranks']) {
            $this->flow[] = $this->file() . $this->ranks['next'];
        }

        // two square advance
        if ($this->rank() === 2 && $this->ranks['start'] == 2) {
            $this->flow[] = $this->file() . ($this->ranks['start'] + 2);
        } elseif ($this->rank() === $square::SIZE['ranks'] - 1 &&
            $this->ranks['start'] == $square::SIZE['ranks'] - 1
        ) {
            $this->flow[] = $this->file() . ($this->ranks['start'] - 2);
        }

        // capture square
        $file = ord($this->file()) - 1;
        if ($file >= 97 && $this->ranks['next'] <= $square::SIZE['ranks']) {
            $this->captureSqs[] = chr($file) . $this->ranks['next'];
        }

        // capture square
        $file = ord($this->file()) + 1;
        if ($file <= 97 + $square::SIZE['files'] - 1 &&
            $this->ranks['next'] <= $square::SIZE['ranks']
        ) {
            $this->captureSqs[] = chr($file) . $this->ranks['next'];
        }
    }

    public function moveSqs(): array
    {
        $sqs = [];

        // flow squares
        foreach ($this->flow as $sq) {
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
        if ($end && $end['id'] === Piece::P && $end['color'] === $this->oppColor()) {
            if ($this->color === Color::W) {
                if ($this->rank() === $this->board->square::SIZE['ranks'] - 3) {
                    $captureSq = $end['to'][0] . ($this->rank() + 1);
                    if (in_array($captureSq, $this->captureSqs)) {
                        $this->enPassantSq = $captureSq;
                        $sqs[] = $captureSq;
                    }
                }
            } elseif ($this->color === Color::B) {
                if ($this->rank() === 4) {
                    $captureSq = $end['to'][0] . ($this->rank() - 1);
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

    public function defendedSqs(): array
    {
        $sqs = [];
        foreach ($this->captureSqs as $sq) {
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

    public function isPromoted(): bool
    {
        $rank = (int) substr($this->move['to'], 1);

        return isset($this->move['newId']) && $rank === $this->ranks['end'];
    }

    public function enPassantPawn(): ?AbstractPiece
    {
        if ($this->enPassantSq) {
            $rank = (int) substr($this->enPassantSq, 1);
            $this->color === Color::W ? $rank-- : $rank++;
            return $this->board->pieceBySq($this->enPassantSq[0] . $rank);
        }

        return null;
    }
}
