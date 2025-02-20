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
     * Set the en passant capture squares.
     *
     * @param \Chess\Variant\Classical\P $pawn
     */
    public function xEnPassantSqs(P $pawn): void
    {
        if (abs($pawn->rank() - (int) substr($pawn->move['to'], 1)) === 2) {
            foreach ($this->board->pieces($this->oppColor()) as $piece) {
                if ($piece->id === Piece::P) {
                    $rank = (int) substr($pawn->move['to'], 1);
                    $enPassantRank = $this->color === Color::W ? $rank - 1 : $rank + 1;
                    $xEnPassantSq = $pawn->move['to'][0] . $enPassantRank;
                    $piece->xEnPassantSq = in_array($xEnPassantSq, $piece->xSqs) 
                        ? $xEnPassantSq 
                        : '';
                }
            }
        } else {
            foreach ($this->board->xEnPassantPawns() as $pawn) {
                $pawn->xEnPassantSq = '';
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
     * Captures a piece.
     */
    public function capture(): void
    {
        if (str_contains($this->move['case'], 'x')) {
            if ($this->xEnPassantSq && $this->move['to'] === $this->xEnPassantSq) {
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
