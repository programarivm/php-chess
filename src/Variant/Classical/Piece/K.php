<?php

namespace Chess\Variant\Classical\Piece;

use Chess\Variant\AbstractPiece;
use Chess\Variant\RType;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class K extends AbstractPiece
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::K);

        $this->mobility = [];

        $rank = $this->rank() + 1;
        if ($rank <= $this->square::SIZE['ranks']) {
            $this->mobility[] = $this->file() . $rank;
        }

        $rank = $this->rank() - 1;
        if ($rank >= 1) {
            $this->mobility[] = $this->file() . $rank;
        }

        $file = ord($this->file()) - 1;
        if ($file >= 97) {
            $this->mobility[] = chr($file) . $this->rank();
        }

        $file = ord($this->file()) + 1;
        if ($file <= 97 + $this->square::SIZE['files'] - 1) {
            $this->mobility[] = chr($file) . $this->rank();
        }

        $file = ord($this->file()) - 1;
        $rank = $this->rank() + 1;
        if ($file >= 97 && $rank <= $this->square::SIZE['ranks']) {
            $this->mobility[] = chr($file) . $rank;
        }

        $file = ord($this->file()) + 1;
        $rank = $this->rank() + 1;
        if ($file <= 97 + $this->square::SIZE['files'] - 1 && $rank <= $this->square::SIZE['ranks']) {
            $this->mobility[] = chr($file) . $rank;
        }

        $file = ord($this->file()) - 1;
        $rank = $this->rank() - 1;
        if ($file >= 97 && $rank >= 1) {
            $this->mobility[] = chr($file) . $rank;
        }

        $file = ord($this->file()) + 1;
        $rank = $this->rank() - 1;
        if ($file <= 97 + $this->square::SIZE['files'] - 1 && $rank >= 1) {
            $this->mobility[] = chr($file) . $rank;
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

    public function sqCastle(string $type): ?string
    {
        $rule = $this->board->castlingRule?->rule[$this->color][Piece::K][$type];
        if ($this->board->castlingRule?->extract($this->board->castlingAbility, $this->color, $type)) {
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
        foreach ($this->mobility as $sq) {
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
        $sqsKing = array_intersect($this->mobility, $this->board->sqCount['free']);

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

    public function isPinned(): bool
    {
        return false;
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
                    $this->square
                )
             );
            $this->board->detach($rook);
            $this->board->attach(
                new R(
                    $rook->color,
                    $this->board->castlingRule->rule[$this->color][Piece::R][rtrim($this->move['pgn'], '+')]['to'],
                    $this->square,
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
}
