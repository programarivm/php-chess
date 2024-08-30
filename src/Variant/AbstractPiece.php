<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

abstract class AbstractPiece
{
    public string $color;

    public string $sq;

    public Square $square;

    public string $id;

    public array $mobility;

    public array $move;

    public AbstractBoard $board;

    public function __construct(string $color, string $sq, Square $square, string $id)
    {
        $this->color = $color;
        $this->sq = $sq;
        $this->square = $square;
        $this->id = $id;
    }

    abstract public function moveSqs(): array;

    abstract public function defendedSqs(): ?array;

    public function file(): string
    {
        return $this->sq[0];
    }

    public function rank(): int
    {
        return (int) substr($this->sq, 1);
    }

    public function oppColor(): string
    {
        return $this->board->color->opp($this->color);
    }

    public function attacked(): ?array
    {
        $attacked = [];
        foreach ($sqs = $this->moveSqs() as $sq) {
            if ($piece = $this->board->pieceBySq($sq)) {
                if ($piece->color === $this->oppColor()) {
                    $attacked[] = $piece;
                }
            }
        }

        return $attacked;
    }

    public function attacking(): ?array
    {
        $attacking = [];
        foreach ($this->board->pieces($this->oppColor()) as $piece) {
            if (in_array($this->sq, $piece->moveSqs())) {
                $attacking[] = $piece;
            }
        }

        return $attacking;
    }

    public function defended(): ?array
    {
        $defended = [];
        foreach ($this->defendedSqs() as $sq) {
            if ($piece = $this->board->pieceBySq($sq)) {
                if ($piece->id !== Piece::K) {
                    $defended[] = $piece;
                }
            }
        }

        return $defended;
    }

    public function defending(): ?array
    {
        $defending = [];
        foreach ($this->board->pieces($this->color) as $piece) {
            if (in_array($this->sq, $piece->defendedSqs())) {
                $defending[] = $piece;
            }
        }

        return $defending;
    }

    public function isAttackingKing(): bool
    {
        foreach ($this->attacked() as $piece) {
            if ($piece->id === Piece::K) {
                return true;
            }
        }

        return false;
    }

    public function isMovable(): bool
    {
        if ($this->move) {
            return in_array($this->move['sq']['next'], $this->moveSqs());
        }

        return false;
    }

    public function isPinned(): bool
    {
        $king = $this->board->piece($this->color, Piece::K);
        $clone = $this->board->clone();
        $clone->detach($clone->pieceBySq($this->sq));
        $clone->refresh();
        $newKing = $clone->piece($this->color, Piece::K);
        $diffPieces = $this->board->diffPieces($king->attacking(), $newKing->attacking());
        foreach ($diffPieces as $diffPiece) {
            if ($diffPiece->color !== $king->color) {
                return true;
            }
        }

        return false;
    }
}
