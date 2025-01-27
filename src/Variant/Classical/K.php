<?php

namespace Chess\Variant\Classical;

use Chess\Variant\AbstractPiece;
use Chess\Variant\RType;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;

class K extends AbstractPiece
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, Piece::K);

        $this->flow = [];

        $rank = $this->rank() + 1;
        if ($rank <= $square::SIZE['ranks']) {
            $this->flow[] = $this->file() . $rank;
        }

        $rank = $this->rank() - 1;
        if ($rank >= 1) {
            $this->flow[] = $this->file() . $rank;
        }

        $file = ord($this->file()) - 1;
        if ($file >= 97) {
            $this->flow[] = chr($file) . $this->rank();
        }

        $file = ord($this->file()) + 1;
        if ($file <= 97 + $square::SIZE['files'] - 1) {
            $this->flow[] = chr($file) . $this->rank();
        }

        $file = ord($this->file()) - 1;
        $rank = $this->rank() + 1;
        if ($file >= 97 && $rank <= $square::SIZE['ranks']) {
            $this->flow[] = chr($file) . $rank;
        }

        $file = ord($this->file()) + 1;
        $rank = $this->rank() + 1;
        if ($file <= 97 + $square::SIZE['files'] - 1 && $rank <= $square::SIZE['ranks']) {
            $this->flow[] = chr($file) . $rank;
        }

        $file = ord($this->file()) - 1;
        $rank = $this->rank() - 1;
        if ($file >= 97 && $rank >= 1) {
            $this->flow[] = chr($file) . $rank;
        }

        $file = ord($this->file()) + 1;
        $rank = $this->rank() - 1;
        if ($file <= 97 + $square::SIZE['files'] - 1 && $rank >= 1) {
            $this->flow[] = chr($file) . $rank;
        }
    }

    public function moveSqs(): array
    {
        $sqs = [
            ...$this->sqsKing(),
            ...$this->sqsCaptures(),
            ...[$this->sqCastle(Castle::LONG)],
            ...[$this->sqCastle(Castle::SHORT)]
        ];

        return array_filter(array_unique($sqs));
    }

    public function defendedSqs(): array
    {
        $sqs = [];
        foreach ($this->flow as $sq) {
            if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    public function sqCastle(string $type): ?string
    {
        if ($type === Castle::SHORT) {
            $id = $this->board->turn === Color::W ? Piece::K : mb_strtolower(Piece::K);
        } else {
            $id = $this->board->turn === Color::W ? Piece::Q : mb_strtolower(Piece::Q);
        }

        if (str_contains($this->board->castlingAbility, $id)) {
            $rule = $this->board->castlingRule?->rule[$this->color][Piece::K][$type];
            if (
                $this->board->turn === $this->color &&
                !$this->board->isCheck() &&
                !array_diff($rule['free'], $this->board->sqCount['free']) &&
                !array_intersect($rule['attack'], $this->board->spaceEval[$this->oppColor()])
            ) {
                return $rule['to'];
            }
        }

        return null;
    }

    protected function sqsCaptures(): ?array
    {
        $sqsCaptures = [];
        foreach ($this->flow as $sq) {
            if ($piece = $this->board->pieceBySq($sq)) {
                if ($this->oppColor() === $piece->color) {
                    if (!$piece->defending()) {
                        $sqsCaptures[] = $sq;
                    }
                }
            }

        }

        return $sqsCaptures;
    }

    protected function sqsKing(): ?array
    {
        $sqsKing = array_intersect($this->flow, $this->board->sqCount['free']);

        return array_diff($sqsKing, $this->board->spaceEval[$this->oppColor()]);
    }

    public function getCastleRook(string $type): ?R
    {
        $rule = $this->board->castlingRule->rule[$this->color][Piece::R][$type];
        if ($piece = $this->board->pieceBySq($rule['from'])) {
            if ($this->sqCastle($type)) {
                return $piece;
            }
        }

        return null;
    }

    public function isPinned(): ?AbstractPiece
    {
        return null;
    }

    /**
     * Castles the king.
     *
     * @param string $rookType
     * @return bool
     */
    public function castle(string $rookType): bool
    {
        if ($rook = $this->getCastleRook($rookType)) {
            $this->board->detach($this->board->pieceBySq($this->sq));
            $this->board->attach(
                new K(
                    $this->color,
                    $this->board->castlingRule->rule[$this->color][Piece::K][rtrim($this->move['pgn'], '+')]['to'],
                    $this->board->square
                )
             );
            $this->board->detach($rook);
            $this->board->attach(
                new R(
                    $rook->color,
                    $this->board->castlingRule->rule[$this->color][Piece::R][rtrim($this->move['pgn'], '+')]['to'],
                    $this->board->square,
                    $rook->type
                )
            );
            $this->updateCastle();
            $this->pushHistory();
            $this->board->refresh();
            return true;
        }

        return false;
    }

    /**
     * Returns true if the king is left in check because of moving the piece.
     *
     * @return bool
     */
    public function isKingLeftInCheck(): bool
    {
        return in_array($this->move['to'], $this->board->spaceEval[$this->oppColor()]);
    }
}
