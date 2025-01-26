<?php

namespace Chess\Variant;

use Chess\Variant\RType;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\N;
use Chess\Variant\Classical\Q;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\CastlingRule;

abstract class AbstractPiece
{
    /**
     * Color.
     *
     * @var string
     */
    public string $color;

    /**
     * Current square.
     *
     * @var string
     */
    public string $sq;

    /**
     * Identifier.
     *
     * @var string
     */
    public string $id;

    /**
     * The mobility of the piece.
     *
     * @var array
     */
    public array $flow;

    /**
     * Current move.
     *
     * @var array
     */
    public array $move;

    /**
     * Board.
     *
     * @var \Chess\Variant\AbstractBoard
     */
    public AbstractBoard $board;

    /**
     * @param string $color
     * @param string $sq
     * @param string $id
     */
    public function __construct(string $color, string $sq, string $id)
    {
        $this->color = $color;
        $this->sq = $sq;
        $this->id = $id;
    }

    /**
     * Returns the piece's moves.
     *
     * @return array
     */
    abstract public function moveSqs(): array;

    /**
     * Returns the defended squares.
     *
     * @return array
     */
    abstract public function defendedSqs(): array;

    /**
     * Returns the piece's file.
     *
     * @return array
     */
    public function file(): string
    {
        return $this->sq[0];
    }

    /**
     * Returns the piece's rank.
     *
     * @return array
     */
    public function rank(): int
    {
        return (int) substr($this->sq, 1);
    }

    /**
     * Returns the opposite color of the piece.
     *
     * @return array
     */
    public function oppColor(): string
    {
        return $this->color === Color::W ? Color::B : Color::W; 
    }

    /**
     * Returns an array of squares connecting this piece to another piece.
     * This is helpful to calculate lines of attack between pieces, lines of
     * defense, and so on.
     *
     * @return array
     */
    public function line(AbstractPiece $piece): array
    {
        $sqs = [];
        if ($this->file() === $piece->file()) {
            if ($this->rank() > $piece->rank()) {
                for ($i = 1; $i < $this->rank() - $piece->rank(); $i++) {
                    $sqs[] = $this->file() . ($piece->rank() + $i);
                }
            } else {
                for ($i = 1; $i < $piece->rank() - $this->rank(); $i++) {
                    $sqs[] = $this->file() . ($piece->rank() - $i);
                }
            }
        } elseif ($this->rank() === $piece->rank()) {
            if ($this->file() > $piece->file()) {
                for ($i = 1; $i < ord($this->file()) - ord($piece->file()); $i++) {
                    $sqs[] = chr(ord($piece->file()) + $i) . $this->rank();
                }
            } else {
                for ($i = 1; $i < ord($piece->file()) - ord($this->file()); $i++) {
                    $sqs[] = chr(ord($piece->file()) - $i) . $this->rank();
                }
            }
        } elseif (abs(ord($this->file()) - ord($piece->file())) ===
            abs(ord($this->rank()) - ord($piece->rank()))
        ) {
            if ($this->file() > $piece->file() && $this->rank() < $piece->rank()) {
                for ($i = 1; $i < $piece->rank() - $this->rank(); $i++) {
                    $sqs[] = chr(ord($piece->file()) + $i) . ($piece->rank() - $i);
                }
            } elseif ($this->file() < $piece->file() && $this->rank() < $piece->rank()) {
                for ($i = 1; $i < $piece->rank() - $this->rank(); $i++) {
                    $sqs[] = chr(ord($piece->file()) - $i) . ($piece->rank() - $i);
                }
            } elseif ($this->file() < $piece->file() && $this->rank() > $piece->rank()) {
                for ($i = 1; $i < $this->rank() - $piece->rank(); $i++) {
                    $sqs[] = chr(ord($piece->file()) - $i) . ($piece->rank() + $i);
                }
            } else {
                for ($i = 1; $i < $this->rank() - $piece->rank(); $i++) {
                    $sqs[] = chr(ord($piece->file()) + $i) . ($piece->rank() + $i);
                }
            }
        }

        return $sqs;
    }

    /**
     * Returns true if the given line of squares is empty of pieces.
     *
     * @return bool
     */
    public function isEmptyLine(array $line): bool
    {
        return !array_diff($line, $this->board->sqCount['free']);
    }

    /**
     * Returns the opponent's pieces being attacked by this piece.
     *
     * @return array
     */
    public function attacked(): array
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

    /**
     * Returns the opponent's pieces attacking this piece.
     *
     * @return array
     */
    public function attacking(): array
    {
        $attacking = [];
        foreach ($this->board->pieces($this->oppColor()) as $piece) {
            if (in_array($this->sq, $piece->moveSqs())) {
                $attacking[] = $piece;
            }
        }

        return $attacking;
    }

    /**
     * Returns the pieces being defended by this piece.
     *
     * @return array
     */
    public function defended(): array
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

    /**
     * Returns the pieces defending this piece.
     *
     * @return array
     */
    public function defending(): array
    {
        $defending = [];
        foreach ($this->board->pieces($this->color) as $piece) {
            if (in_array($this->sq, $piece->defendedSqs())) {
                $defending[] = $piece;
            }
        }

        return $defending;
    }

    /**
     * Returns true if this piece is attacking the opponent's king.
     *
     * @return bool
     */
    public function isAttackingKing(): bool
    {
        foreach ($this->attacked() as $piece) {
            if ($piece->id === Piece::K) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the piece can be moved.
     *
     * @return bool
     */
    public function isMovable(): bool
    {
        return in_array($this->move['to'], $this->moveSqs());
    }

    /**
     * Returns true if this piece is aligned with another one in relation to
     * a target piece.
     *
     * @return bool
     */
    public function isAlignedWith(AbstractPiece $withPiece, AbstractPiece $targetPiece): bool
    {
        $a = $this->line($targetPiece);
        $b = $withPiece->line($targetPiece);
        if (!empty(array_intersect($a, $b))) {
            return true;
        } elseif (in_array($withPiece->sq, $a)) {
            return true;
        } elseif (in_array($this->sq, $b)) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if the piece is pinned.
     *
     * @return bool
     */
    public function isPinned(): bool
    {
        foreach ($this->attacking() as $attacking) {
            if (is_a($attacking, AbstractLinePiece::class)) {
                $king = $this->board->piece($this->color, Piece::K);
                if ($this->isAlignedWith($king, $attacking) && 
                    $this->isEmptyLine($this->line($king))
                ) { 
                        return true;
                }
            }
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
        $isCheck = false;
        $turn = $this->board->turn;
        $history = $this->board->history;
        $castlingAbility = $this->board->castlingAbility;
        $sqCount = $this->board->sqCount;
        $spaceEval = $this->board->spaceEval;
        $pieces = $this->board->pieces();
        if ($this->move()) {
            $isCheck = $this->board->piece($this->color, Piece::K)?->attacking() != [];
            $this->board->turn = $turn;
            $this->board->history = $history;
            $this->board->castlingAbility = $castlingAbility;
            $this->board->sqCount = $sqCount;
            $this->board->spaceEval = $spaceEval;
            $this->board->rewind();
            while ($this->board->valid()) {
                $piece = $this->board->current();
                $this->board->next();
                $this->board->detach($piece);
            }
            foreach ($pieces as $val) {
                $this->board->attach($val);
            }
        }

        return $isCheck;
    }

    /**
     * Makes a move.
     *
     * @return bool
     */
    public function move(): bool
    {
        $this->capture();
        $this->board->detach($this->board->pieceBySq($this->sq));
        $class = VariantType::getClass($this->board->variant, $this->id);
        $this->board->attach(new $class(
            $this->color,
            $this->move['to'],
            $this->board->square,
            $this->id === Piece::R ? $this->type : null
        ));
        $this->promotion();
        $this->updateCastle();
        $this->pushHistory();
        $this->board->refresh();

        return true;
    }

    /**
     * Piece promotion.
     */
    public function promotion(): void
    {
        if ($this->id === Piece::P) {
            if ($this->isPromoted($this->board->square)) {
                $this->board->detach($this->board->pieceBySq($this->move['to']));
                if (!isset($this->move['newId'])) {
                    $this->board->attach(new Q(
                        $this->color,
                        $this->move['to'],
                        $this->board->square
                    ));
                } elseif ($this->move['newId'] === Piece::N) {
                    $this->board->attach(new N(
                        $this->color,
                        $this->move['to'],
                        $this->board->square
                    ));
                } elseif ($this->move['newId'] === Piece::B) {
                    $this->board->attach(new B(
                        $this->color,
                        $this->move['to'],
                        $this->board->square
                    ));
                } elseif ($this->move['newId'] === Piece::R) {
                    $this->board->attach(new R(
                        $this->color,
                        $this->move['to'],
                        $this->board->square,
                        RType::R
                    ));
                } elseif ($this->move['newId'] === Piece::Q) {
                    $this->board->attach(new Q(
                        $this->color,
                        $this->move['to'],
                        $this->board->square
                    ));
                }
            }
        }
    }

    /**
     * Captures a piece.
     */
    public function capture(): void
    {
        if (str_contains($this->move['case'], 'x')) {
            if ($piece = $this->board->pieceBySq($this->move['to'])) {
                $this->board->detach($piece);
            }
        }
    }

    /**
     * Updates the castling ability.
     *
     * @return \Chess\Variant\AbstractPiece
     */
    public function updateCastle(): AbstractPiece
    {
        if ($this->id === Piece::K) {
            $search = $this->board->turn === Color::W ? 'KQ' : 'kq';
            $this->board->castlingAbility = str_replace($search, '', $this->board->castlingAbility)
                ?: CastlingRule::NEITHER;
        } elseif ($this->id === Piece::R) {
            if ($this->type === RType::CASTLE_SHORT) {
                $search = $this->board->turn === Color::W ? 'K' : 'k';
                $this->board->castlingAbility = str_replace($search, '', $this->board->castlingAbility)
                    ?: CastlingRule::NEITHER;
            } elseif ($this->type === RType::CASTLE_LONG) {
                $search = $this->board->turn === Color::W ? 'Q' : 'q';
                $this->board->castlingAbility = str_replace($search, '', $this->board->castlingAbility)
                    ?: CastlingRule::NEITHER;
            }
        }

        return $this;
    }

    /**
     * Adds a new element to the history array.
     */
    public function pushHistory(): void
    {
        $this->board->history[] = [
            'pgn' => $this->move['pgn'],
            'color' => $this->move['color'],
            'id' => $this->move['id'],
            'from' => $this->sq,
            'to' => $this->move['to'],
        ];
    }
}
