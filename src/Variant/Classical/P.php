<?php

namespace Chess\Variant\Classical;

use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class P extends AbstractPiece
{
    public array $ranks;

    public array $captureSqs;

    public string $enPassant = '';

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
        $end = end($this->board->history);
        if ($end && $end['id'] === Piece::P && $end['color'] === $this->oppColor()) {
            if ('-' !== explode(' ', $end['fen'])[3]) {
                if ($this->color === Color::W) {
                    if ($this->rank() === $this->board->square::SIZE['ranks'] - 3) {
                        $captureSq = $end['to'][0] . ($this->rank() + 1);
                        if (in_array($captureSq, $this->captureSqs)) {
                            $this->enPassant = $captureSq;
                            $sqs[] = $captureSq;
                        }
                    }
                } elseif ($this->color === Color::B) {
                    if ($this->rank() === 4) {
                        $captureSq = $end['to'][0] . ($this->rank() - 1);
                        if (in_array($captureSq, $this->captureSqs)) {
                            $this->enPassant = $captureSq;
                            $sqs[] = $captureSq;
                        }
                    }
                }
            }
        } else {
            $sqs[] = $this->enPassant;
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
        return (int) substr($this->move['to'], 1) === $this->ranks['end'];
    }

    public function enPassantPawn(): ?AbstractPiece
    {
        if ($this->enPassant) {
            $rank = (int) substr($this->enPassant, 1);
            $this->color === Color::W ? $rank-- : $rank++;
            return $this->board->pieceBySq($this->enPassant[0] . $rank);
        }

        return null;
    }
}
