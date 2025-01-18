<?php

namespace Chess\Variant\Classical;

use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class P extends AbstractPiece
{
    public array $captureSqs;

    public string $enPassant = '';

    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::P);

        $this->captureSqs = [];

        $this->flow = [];

        // next rank
        if ($this->nextRank() <= $square::SIZE['ranks']) {
            $this->flow[] = $this->file() . $this->nextRank();
        }

        // two square advance
        if ($this->rank() === 2 && $this->startRank($square) == 2) {
            $this->flow[] = $this->file() . ($this->startRank($square) + 2);
        } elseif ($this->rank() === $square::SIZE['ranks'] - 1 &&
            $this->startRank($square) == $square::SIZE['ranks'] - 1
        ) {
            $this->flow[] = $this->file() . ($this->startRank($square) - 2);
        }

        // capture square
        $file = ord($this->file()) - 1;
        if ($file >= 97 && $this->nextRank() <= $square::SIZE['ranks']) {
            $this->captureSqs[] = chr($file) . $this->nextRank();
        }

        // capture square
        $file = ord($this->file()) + 1;
        if ($file <= 97 + $square::SIZE['files'] - 1 &&
            $this->nextRank() <= $square::SIZE['ranks']
        ) {
            $this->captureSqs[] = chr($file) . $this->nextRank();
        }
    }

    public function startRank(Square $square) 
    {
        if ($this->color === Color::W) {
            return 2;
        }

        return $square::SIZE['ranks'] - 1;
    }

    public function nextRank() 
    {
        if ($this->color === Color::W) {
            return $this->rank() + 1;
        }

        return $this->rank() - 1;
    }

    public function promoRank(Square $square) 
    {
        if ($this->color === Color::W) {
            return $square::SIZE['ranks'];
        }

        return 1;
    }

    public function isPromoted(Square $square): bool
    {
        return (int) substr($this->move['to'], 1) === $this->promoRank($square);
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
        if ($end && $end['color'] === $this->oppColor() && '-' != explode(' ', $end['fen'])[3]) {
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
