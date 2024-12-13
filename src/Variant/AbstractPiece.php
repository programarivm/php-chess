<?php

namespace Chess\Variant;

use Chess\Variant\RType;
use Chess\Variant\Classical\Piece\B;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\Q;
use Chess\Variant\Classical\Piece\R;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

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
     * Square.
     *
     * @var \Chess\Variant\Classical\PGN\AN\Square
     */
    public Square $square;

    /**
     * Identifier.
     *
     * @var string
     */
    public string $id;

    /**
     * Mobility.
     *
     * @var array
     */
    public array $mobility;

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
     * @param \Chess\Variant\Classical\PGN\AN\Square $square
     * @param string $id
     */
    public function __construct(string $color, string $sq, Square $square, string $id)
    {
        $this->color = $color;
        $this->sq = $sq;
        $this->square = $square;
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
     * Returns an array representing the line of attack of this piece.
     *
     * @return array
     */
    abstract public function lineOfAttack(): array;

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
        return $this->board->color->opp($this->color);
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
     * Returns true if the piece is pinned.
     *
     * @return bool
     */
    public function isPinned(): bool
    {
        $before = $this->board->piece($this->color, Piece::K)->attacking();
        $this->board->detach($this);
        $this->board->refresh();
        $after = $this->board->piece($this->color, Piece::K)->attacking();
        $this->board->attach($this);
        $this->board->refresh();

        return $this->board->diffPieces($before, $after) !== [];
    }

    /**
     * Returns true if the king is left in check.
     *
     * @return bool
     */
    public function isLeftInCheck(): bool
    {
        $isLeftInCheck = false;
        $turn = $this->board->turn;
        $history = $this->board->history;
        $castlingAbility = $this->board->castlingAbility;
        $sqCount = $this->board->sqCount;
        $spaceEval = $this->board->spaceEval;
        $pieces = $this->board->pieces();
        if ($this->move()) {
            $isLeftInCheck = $this->board->piece($this->color, Piece::K)?->attacking() != [];
            $this->board->turn = $turn;
            $this->board->history = $history;
            $this->board->castlingAbility = $castlingAbility;
            $this->board->sqCount = $sqCount;
            $this->board->spaceEval = $spaceEval;
            foreach ($this->board->pieces() as $val) {
                $this->board->detach($val);
            }
            foreach ($pieces as $val) {
                $this->board->attach($val);
            }
        }

        return $isLeftInCheck;
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
            if ($this->isPromoted()) {
                $this->board->detach($this->board->pieceBySq($this->move['to']));
                if ($this->move['newId'] === Piece::N) {
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
                } else {
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
            if ($this->id === Piece::P &&
                $this->enPassantSq &&
                !$this->board->pieceBySq($this->move['to'])
            ) {
                $captured = $this->enPassantPawn();
            } else {
                $captured = $this->board->pieceBySq($this->move['to']);
            }
            $captured ? $this->board->detach($captured) : null;
        }
    }

    /**
     * Updates the castle property.
     *
     * @return \Chess\Variant\AbstractPiece
     */
    public function updateCastle(): AbstractPiece
    {
        if ($this->board->castlingRule?->can($this->board->castlingAbility, $this->board->turn)) {
            if ($this->id === Piece::K) {
                $search = $this->board->turn === Color::W ? 'KQ' : 'kq';
                $this->board->castlingAbility = str_replace($search, '', $this->board->castlingAbility);
                $this->board->castlingAbility = $this->board->castlingAbility ?: CastlingRule::NEITHER;
            } elseif ($this->id === Piece::R) {
                if ($this->type === RType::CASTLE_SHORT) {
                    $search = $this->board->turn === Color::W ? 'K' : 'k';
                } elseif ($this->type === RType::CASTLE_LONG) {
                    $search = $this->board->turn === Color::W ? 'Q' : 'q';
                }
                $this->board->castlingAbility = str_replace($search, '', $this->board->castlingAbility);
                $this->board->castlingAbility = $this->board->castlingAbility ?: CastlingRule::NEITHER;
            }
        }

        return $this;
    }

    /**
     * Adds a new move to the history.
     */
    public function pushHistory(): void
    {
        $this->move['from'] = $this->sq;
        $this->board->history[] = $this->move;
    }
}
