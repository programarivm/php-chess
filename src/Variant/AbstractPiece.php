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
     * Returns the squares defended by this piece.
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
     * Returns the squares connecting this piece to the given square.
     * 
     * @param string $sq
     * @return array
     */
    public function line(string $sq): array
    {
        $file = $sq[0];
        $rank = (int) substr($sq, 1);
        $sqs = [];
        if ($this->file() === $file) {
            if ($this->rank() > $rank) {
                for ($i = 1; $i < $this->rank() - $rank; $i++) {
                    $sqs[] = $this->file() . ($rank + $i);
                }
            } else {
                for ($i = 1; $i < $rank - $this->rank(); $i++) {
                    $sqs[] = $this->file() . ($rank - $i);
                }
            }
        } elseif ($this->rank() === $rank) {
            if ($this->file() > $file) {
                for ($i = 1; $i < ord($this->file()) - ord($file); $i++) {
                    $sqs[] = chr(ord($file) + $i) . $this->rank();
                }
            } else {
                for ($i = 1; $i < ord($file) - ord($this->file()); $i++) {
                    $sqs[] = chr(ord($file) - $i) . $this->rank();
                }
            }
        } elseif (abs(ord($this->file()) - ord($file)) ===
            abs(ord($this->rank()) - ord($rank))
        ) {
            if ($this->file() > $file && $this->rank() < $rank) {
                for ($i = 1; $i < $rank - $this->rank(); $i++) {
                    $sqs[] = chr(ord($file) + $i) . ($rank - $i);
                }
            } elseif ($this->file() < $file && $this->rank() < $rank) {
                for ($i = 1; $i < $rank - $this->rank(); $i++) {
                    $sqs[] = chr(ord($file) - $i) . ($rank - $i);
                }
            } elseif ($this->file() < $file && $this->rank() > $rank) {
                for ($i = 1; $i < $this->rank() - $rank; $i++) {
                    $sqs[] = chr(ord($file) - $i) . ($rank + $i);
                }
            } else {
                for ($i = 1; $i < $this->rank() - $rank; $i++) {
                    $sqs[] = chr(ord($file) + $i) . ($rank + $i);
                }
            }
        }

        return $sqs;
    }

    /**
     * Returns true if the given line of squares is empty of pieces.
     *
     * @param array $line
     * @return bool
     */
    public function isEmpty(array $line): bool
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
     * Returns true if this piece is between the given two pieces.
     *
     * @param \Chess\Variant\AbstractPiece $a
     * @param \Chess\Variant\AbstractPiece $b
     * @return bool
     */
    public function isBetween(AbstractPiece $a, AbstractPiece $b): bool
    {
        return in_array($this->sq, $a->line($b->sq));
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
                if ($this->isBetween($piece, $king) && $this->isEmpty($this->line($king->sq))) { 
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
                if ($this->move['to'] !== $piece->sq && 
                    !in_array($this->move['to'], $piece->line($king->sq))
                ) {
                    return true;
                }
            }
        }        
        if ($pinning = $this->isPinned()) {
            if ($this->move['to'] !== $pinning->sq && 
                !in_array($this->move['to'], $pinning->line($king->sq))) {
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
        $this->capture();
        $this->board->detach($this->board->pieceBySq($this->sq));
        $class = VariantType::getClass($this->board->variant, $this->id);
        $this->board->attach(new $class(
            $this->color,
            $this->move['to'],
            $this->board->square,
            $this->id === Piece::R ? $this->type : null
        ));
        $this->enPassant();
        $this->promotion();
        $this->updateCastle();
        $this->pushHistory();
        $this->board->refresh();

        return true;
    }

    public function enPassant(): void
    {
        if ($this->id === Piece::P) { 
            $rankFrom = (int) substr($this->sq, 1);
            $rankTo = (int) substr($this->move['to'], 1);
            $diff = abs($rankFrom - $rankTo);
            if ($diff === 2) {
                $this->enPassantSq($this->move['to']);
            } else {
                foreach ($this->board->pieces($this->oppColor()) as $piece) {
                    if ($piece->id === Piece::P) {
                        $piece->enPassant = '';
                    }
                }
            }
         } else {
            foreach ($this->board->pieces($this->oppColor()) as $piece) {
                if ($piece->id === Piece::P) {
                    $piece->enPassant = '';
                }
            }
        }
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
