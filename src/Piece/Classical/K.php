<?php

namespace Chess\Piece\Classical;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\AbstractPiece;
use Chess\Piece\RType;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class K extends AbstractPiece
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::K);

        $this->mobility = [];

        try {
            $file = $this->sq[0];
            $rank = $this->rank() + 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = $this->sq[0];
            $rank = $this->rank() - 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank();
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank();
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() + 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() + 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank() - 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank() - 1;
            if ($this->square->validate($file . $rank)) {
                $this->mobility[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {
        }
    }

    public function legalSqs(): array
    {
        $sqs = [
            ...$this->sqsKing(),
            ...$this->sqsCaptures(),
            ...[$this->sqCastleLong()],
            ...[$this->sqCastleShort()]
        ];

        return array_filter(array_unique($sqs));
    }

    public function defendedSqs(): ?array
    {
        $sqs = [];
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->sqCount['used'][$this->color])) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    public function sqCastleLong(): ?string
    {
        $rule = $this->board->castlingRule->rule[$this->color][Piece::K][Castle::LONG];

        if ($this->board->castlingRule->long($this->board->castlingAbility, $this->color)) {
            if (
                ($this->board->turn === $this->color && !$this->board->isCheck()) &&
                !array_diff($rule['free'], $this->board->sqCount['free']) &&
                empty(array_intersect($rule['attack'], $this->board->spaceEval[$this->oppColor()]))
            ) {
                return $rule['sq']['next'];
            }
        }

        return null;
    }

    public function sqCastleShort(): ?string
    {
        $rule = $this->board->castlingRule->rule[$this->color][Piece::K][Castle::SHORT];

        if ($this->board->castlingRule->short($this->board->castlingAbility, $this->color)) {
            if (
                ($this->board->turn === $this->color && !$this->board->isCheck()) &&
                !array_diff($rule['free'], $this->board->sqCount['free']) &&
                empty(array_intersect($rule['attack'], $this->board->spaceEval[$this->oppColor()]))
            ) {
                return $rule['sq']['next'];
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
                    if (empty($piece->defending())) {
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
        if ($type === RType::CASTLE_LONG && $this->sqCastleLong()) {
            if ($piece = $this->board->pieceBySq($rule['sq']['current'])) {
                if ($piece->id === Piece::R) {
                    return $piece;
                }
            }
        } elseif ($type === RType::CASTLE_SHORT && $this->sqCastleShort()) {
            if ($piece = $this->board->pieceBySq($rule['sq']['current'])) {
                if ($piece->id === Piece::R) {
                    return $piece;
                }
            }
        }

        return null;
    }

    public function isPinned(): bool
    {
        return false;
    }
}
