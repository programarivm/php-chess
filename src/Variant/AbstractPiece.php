<?php

namespace Chess\Variant;

use Chess\Variant\RType;
use Chess\Variant\Classical\B;
use Chess\Variant\Classical\N;
use Chess\Variant\Classical\Q;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Move;
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
     * Returns the squares defended by this piece.
     *
     * @return array
     */
    abstract public function defendedSqs(): array;

    /**
     * Returns the piece's file.
     *
     * @return string
     */
    public function file(): string
    {
        return $this->sq[0];
    }

    /**
     * Returns the piece's rank.
     *
     * @return int
     */
    public function rank(): int
    {
        return (int) substr($this->sq, 1);
    }

    /**
     * Returns the opposite color of the piece.
     *
     * @return string
     */
    public function oppColor(): string
    {
        return $this->color === Color::W ? Color::B : Color::W; 
    }

    /**
     * Returns the opponent's pieces being attacked by this piece.
     *
     * @return array
     */
    public function attacked(): array
    {
        $pieces = [];
        foreach ($this->moveSqs() as $sq) {
            if ($piece = $this->board->pieceBySq($sq)) {
                if ($piece->color === $this->oppColor()) {
                    $pieces[] = $piece;
                }
            }
        }

        return $pieces;
    }

    /**
     * Returns the opponent's pieces attacking this piece.
     *
     * @return array
     */
    public function attacking(): array
    {
        $pieces = [];
        foreach ($this->board->pieces($this->oppColor()) as $piece) {
            if (in_array($this->sq, $piece->moveSqs())) {
                $pieces[] = $piece;
            }
        }

        return $pieces;
    }

    /**
     * Returns the pieces being defended by this piece.
     *
     * @return array
     */
    public function defended(): array
    {
        $pieces = [];
        foreach ($this->defendedSqs() as $sq) {
            if ($piece = $this->board->pieceBySq($sq)) {
                if ($piece->id !== Piece::K) {
                    $pieces[] = $piece;
                }
            }
        }

        return $pieces;
    }

    /**
     * Returns the pieces defending this piece.
     *
     * @return array
     */
    public function defending(): array
    {
        $pieces = [];
        foreach ($this->board->pieces($this->color) as $piece) {
            if (in_array($this->sq, $piece->defendedSqs())) {
                $pieces[] = $piece;
            }
        }

        return $pieces;
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
     * Returns the pinning piece.
     *
     * @return \Chess\Variant\AbstractPiece
     */
    public function isPinned(): ?AbstractPiece
    {
        foreach ($this->attacking() as $piece) {
            if (is_a($piece, AbstractLinePiece::class)) {
                $king = $this->board->piece($this->color, Piece::K);
                if ($this->board->square->isBetweenSqs($piece->sq, $this->sq, $king->sq) &&
                    $this->board->isEmptyLine($this->board->square->line($this->sq, $king->sq))
                ) {
                    return $piece;
                }
            }
        }

        return null;
    }

    /**
     * Returns true if the king is left in check because of moving the piece.
     *
     * @return bool
     */
    public function isKingLeftInCheck(): bool
    {
        if ($king = $this->board->piece($this->color, Piece::K)) {
            foreach ($king->attacking() as $piece) {
                if ($piece->id === Piece::P) {
                    $rank = (int) substr($piece->sq, 1);
                    $enPassantRank = $piece->color === Color::W ? $rank - 1 : $rank + 1;
                    $xEnPassantSq = $piece->sq[0] . $enPassantRank;
                    if ($this->move['to'] !== $piece->sq && $this->move['to'] !== $xEnPassantSq) {
                        return true;
                    }
                } else {
                    if ($this->move['to'] !== $piece->sq &&
                        !in_array($this->move['to'], $this->board->square->line($piece->sq, $king->sq))
                    ) {
                        return true;
                    }
                }
            }
        }        
        if ($pinning = $this->isPinned()) {
            if ($this->move['to'] !== $pinning->sq && 
                !in_array($this->move['to'], $this->board->square->line($pinning->sq, $king->sq))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Makes a move.
     *
     * @return bool
     */
    public function move(): bool
    {
        $this->enPassant();
        $this->capture();
        $this->board->detach($this);
        $class = $this::class;
        $this->board->attach(new $class(
            $this->color,
            $this->move['to'],
            $this->board->square,
            $this->id === Piece::R ? $this->type : null
        ));
        $this->promote();
        $this->updateCastle();
        $this->updateHalfMoveClock();
        $this->pushHistory();
        $this->board->refresh();

        return true;
    }

    /**
     * Set the en passant capture squares.
     */
    public function enPassant(): void
    {
        if ($this->id === Piece::P) {
            $this->xEnPassantSqs($this);
        } else {
            foreach ($this->board->xEnPassantPawns() as $pawn) {
                $pawn->xEnPassantSq = '';
            }
        }
    }

    /**
     * Pawn promotion to piece.
     */
    public function promote(): void
    {
        if ($this->id === Piece::P && 
            $this->board->square->promoRank($this->color) === (int) substr($this->move['to'], 1)
        ) {
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
        $search = '';
        if ($this->id === Piece::K) {
            $search = $this->board->turn === Color::W ? 'KQ' : 'kq';
        } elseif ($this->id === Piece::R) {
            if ($this->type === RType::CASTLE_SHORT) {
                $search = $this->board->turn === Color::W ? 'K' : 'k';
            } elseif ($this->type === RType::CASTLE_LONG) {
                $search = $this->board->turn === Color::W ? 'Q' : 'q';
            }
        }
        if ($search) {
            $this->board->castlingAbility = str_replace($search, '', $this->board->castlingAbility) 
                ?: CastlingRule::NEITHER;
        }

        return $this;
    }

    /**
     * Updates the number of halfmoves since the last capture or pawn advance.
     */
    public function updateHalfMoveClock(): void
    {
        if (str_contains($this->move['case'], 'x')) {
            $this->board->halfMoveClock = 0;
        } elseif (
            $this->move['case'] === $this->board->move->case(Move::PAWN) ||
            $this->move['case'] === $this->board->move->case(Move::PAWN_PROMOTES)
        ) {
            $this->board->halfMoveClock = 0;
        }

        $this->board->halfMoveClock += 1;
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
