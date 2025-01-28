<?php

namespace Chess\Variant\Classical;

use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class P extends AbstractPiece
{
    /**
     * Capture squares.
     *
     * @var array
     */
    public array $xSqs;

    /**
     * En passant capture square.
     *
     * @var string
     */
    public string $xEnPassantSq = '';

    /**
     * @param string $color
     * @param string $sq
     * @param \Chess\Variant\Classical\PGN\Square $square
     */
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::P);

        $this->xSqs = [];

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
            $this->xSqs[] = chr($file) . $this->nextRank();
        }

        // capture square
        $file = ord($this->file()) + 1;
        if ($file <= 97 + $square::SIZE['files'] - 1 &&
            $this->nextRank() <= $square::SIZE['ranks']
        ) {
            $this->xSqs[] = chr($file) . $this->nextRank();
        }
    }

    /**
     * Returns the piece's moves.
     *
     * @return array
     */
    public function moveSqs(): array
    {
        $sqs = [];
        foreach ($this->flow as $sq) {
            if (in_array($sq, $this->board->sqCount['free'])) {
                $sqs[] = $sq;
            } else {
                break;
            }
        }
        foreach ($this->xSqs as $sq) {
            if (in_array($sq, $this->board->sqCount['used'][$this->oppColor()])) {
                $sqs[] = $sq;
            }
        }
        $sqs[] = $this->xEnPassantSq;

        return array_filter(array_unique($sqs));
    }

    /**
     * Returns the squares defended by this piece.
     *
     * @return array
     */
    public function defendedSqs(): array
    {
        $sqs = [];
        foreach ($this->xSqs as $sq) {
            if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    /**
     * Set the en passant capture square.
     */
    public function xEnPassantSq(string $sq): void
    {
        foreach ($this->board->pieces($this->oppColor()) as $piece) {
            if ($piece->id === Piece::P) {
                $rank = (int) substr($sq, 1);
                $rank = $this->color === Color::W ? $rank - 1 : $rank + 1;
                $xEnPassantSq = $sq[0] . $rank;
                if (in_array($xEnPassantSq, $piece->xSqs)) {
                    $piece->xEnPassantSq = $xEnPassantSq;
                }
            }
        }
    }

    /**
     * Returns the start rank.
     *
     * @param \Chess\Variant\Classical\PGN\Square $square
     * @return int
     */
    public function startRank(Square $square): int
    {
        if ($this->color === Color::W) {
            return 2;
        }

        return $square::SIZE['ranks'] - 1;
    }

    /**
     * Returns the next rank.
     *
     * @return int
     */
    public function nextRank(): int
    {
        if ($this->color === Color::W) {
            return $this->rank() + 1;
        }

        return $this->rank() - 1;
    }

    /**
     * Returns the promotion rank.
     *
     * @param \Chess\Variant\Classical\PGN\Square $square
     * @return int
     */
    public function promoRank(Square $square): int
    {
        if ($this->color === Color::W) {
            return $square::SIZE['ranks'];
        }

        return 1;
    }

    /**
     * Returns true if the pawn is promoted.
     *
     * @param \Chess\Variant\Classical\PGN\Square $square
     * @return bool
     */
    public function isPromoted(Square $square): bool
    {
        return (int) substr($this->move['to'], 1) === $this->promoRank($square);
    }

    /**
     * Captures a piece.
     */
    public function capture(): void
    {
        if (str_contains($this->move['case'], 'x')) {
            if ($this->xEnPassantSq) { 
                $rank = (int) substr($this->xEnPassantSq, 1);
                $rank = $this->color === Color::W ? $rank - 1 : $rank + 1;
                if ($piece = $this->board->pieceBySq($this->xEnPassantSq[0] . $rank)) {
                    $this->board->detach($piece);
                }
            } elseif ($piece = $this->board->pieceBySq($this->move['to'])) {
                $this->board->detach($piece);
            }
        }
    }
}
