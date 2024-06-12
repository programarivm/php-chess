<?php

namespace Chess\Variant\Classical;

use Chess\Exception\BoardException;
use Chess\Piece\AbstractPiece;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

/**
 * AbstractPgnParser
 *
 * The root class in the hierarchy of chess boards defines the getter and the
 * setter methods, which are public methods, in addition to implementing the
 * internal methods required to convert PGN moves into a data structure.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class AbstractPgnParser extends \SplObjectStorage
{
    /**
     * Current player's turn.
     *
     * @var string
     */
    public string $turn = '';

    /**
     * Captured pieces.
     *
     * @var array
     */
    public array $captures = [
        Color::W => [],
        Color::B => [],
    ];

    /**
     * History.
     *
     * @var array
     */
    public array $history = [];

    /**
     * Color.
     *
     * @var \Chess\Variant\Classical\PGN\AN\Color
     */
    public Color $color;

    /**
     * Castling rule.
     *
     * @var \Chess\Variant\Classical\Rule\CastlingRule
     */
    public CastlingRule $castlingRule;

    /**
     * Castling ability.
     *
     * @var string
     */
    public string $castlingAbility = '';

    /**
     * Start FEN position.
     *
     * @var string
     */
    public string $startFen = '';

    /**
     * Square.
     *
     * @var \Chess\Variant\Classical\PGN\Square
     */
    public Square $square;

    /**
     * Move.
     *
     * @var \Chess\Variant\Classical\PGN\Move
     */
    public Move $move;

    /**
     * Space evaluation.
     *
     * @var object
     */
    public object $spaceEval;

    /**
     * Count squares.
     *
     * @var object
     */
    public object $sqCount;

    /**
     * Picks a piece from the board.
     *
     * @param array $move
     * @return array
     */
    protected function pickPiece(array $move): array
    {
        $pieces = [];
        foreach ($this->getPieces($move['color']) as $piece) {
            if ($piece->id === $move['id']) {
                if (strstr($piece->sq, $move['sq']['current'])) {
                    $piece->move = $move;
                    $pieces[] = $piece;
                }
            }
        }

        return $pieces;
    }

    /**
     * Returns true if the move is syntactically valid.
     *
     * @param array $move
     * @return bool
     */
    protected function isValidMove(array $move): bool
    {
        if ($this->isAmbiguousCapture($move)) {
            return false;
        } elseif ($this->isAmbiguousMove($move)) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if the capture move is ambiguous.
     *
     * @param array $move
     * @return bool
     */
    protected function isAmbiguousCapture(array $move): bool
    {
        if ($move['isCapture']) {
            if ($move['id'] === Piece::P) {
                $enPassant = $this->history
                    ? $this->enPassant()
                    : explode(' ', $this->startFen)[3];
                if (!$this->getPieceBySq($move['sq']['next']) && $enPassant !== $move['sq']['next']) {
                    return true;
                }
            } else {
                if (!$this->getPieceBySq($move['sq']['next'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if the move is ambiguous.
     *
     * @param array $move
     * @return bool
     */
    protected function isAmbiguousMove(array $move): bool
    {
        $ambiguous = [];
        foreach ($this->pickPiece($move) as $piece) {
            if (in_array($move['sq']['next'], $piece->sqs())) {
                if (!$this->isPinned($piece)) {
                    $ambiguous[] = $move['sq']['next'];
                }
            }
        }

        return count($ambiguous) > 1;
    }

    /**
     * Returns true if the move is legal.
     *
     * @param array $move
     * @return bool
     */
    protected function isLegalMove(array $move): bool
    {
        foreach ($pieces = $this->pickPiece($move) as $piece) {
            if ($piece->isMovable()) {
                if (!$this->isPinned($piece)) {
                    if ($piece->move['type'] === $this->move->case(Move::CASTLE_SHORT)) {
                        return $this->castle($piece, RType::CASTLE_SHORT);
                    } elseif ($piece->move['type'] === $this->move->case(Move::CASTLE_LONG)) {
                        return $this->castle($piece, RType::CASTLE_LONG);
                    } else {
                        return $this->move($piece);
                    }
                }
            }
        }

        return false;
    }

    /**
     * Makes a move.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return bool
     */
    protected function move(AbstractPiece $piece): bool
    {
        if ($piece->move['isCapture']) {
            $this->capture($piece);
        }
        if ($toDetach = $this->getPieceBySq($piece->sq)) {
            $this->detach($toDetach);
        }
        $class = "\\Chess\\Piece\\{$piece->id}";
        $this->attach(new $class(
            $piece->color,
            $piece->move['sq']['next'],
            $this->square,
            $piece->id === Piece::R ? $piece->getType() : null
        ));
        if ($piece->id === Piece::P) {
            if ($piece->isPromoted()) {
                $this->promote($piece);
            }
        }
        $this->updateCastle($piece)->pushHistory($piece)->refresh();

        return true;
    }

    /**
     * Castles the king.
     *
     * @param \Chess\Piece\K $king
     * @param string $rookType
     * @return bool
     */
    protected function castle(K $king, string $rookType): bool
    {
        if ($rook = $king->getCastleRook($rookType)) {
            $this->detach($this->getPieceBySq($king->sq));
            $this->attach(
                new K(
                    $king->color,
                    $this->castlingRule->getRule()[$king->color][Piece::K][rtrim($king->move['pgn'], '+')]['sq']['next'],
                    $this->square
                )
             );
            $this->detach($rook);
            $this->attach(
                new R(
                    $rook->color,
                    $this->castlingRule->getRule()[$king->color][Piece::R][rtrim($king->move['pgn'], '+')]['sq']['next'],
                    $this->square,
                    $rook->getType()
                )
            );
            $this->castlingAbility = $this->castlingRule->castle($this->castlingAbility, $this->turn);
            $this->pushHistory($king)->refresh();
            return true;
        }

        return false;
    }

    /**
     * Updates the castle property.
     *
     * @param \Chess\Piece\AbstractPiece $piece The moved piece
     * @return \Chess\Variant\Classical\Board
     */
    protected function updateCastle(AbstractPiece $piece): Board
    {
        if ($this->castlingRule->can($this->castlingAbility, $this->turn)) {
            if ($piece->id === Piece::K) {
                $this->castlingAbility = $this->castlingRule->remove(
                    $this->castlingAbility,
                    $this->turn,
                    [Piece::K, Piece::Q]
                );
            } elseif ($piece->id === Piece::R) {
                if ($piece->getType() === RType::CASTLE_SHORT) {
                    $this->castlingAbility = $this->castlingRule->remove(
                        $this->castlingAbility,
                        $this->turn,
                        [Piece::K]
                    );
                } elseif ($piece->getType() === RType::CASTLE_LONG) {
                    $this->castlingAbility = $this->castlingRule->remove(
                        $this->castlingAbility,
                        $this->turn,
                        [Piece::Q]
                    );
                }
            }
        }
        $oppColor = $this->color->opp($this->turn);
        if ($this->castlingRule->can($this->castlingAbility, $oppColor)) {
            if ($piece->move['isCapture']) {
                if ($piece->move['sq']['next'] ===
                    $this->castlingRule->getRule()[$oppColor][Piece::R][Castle::SHORT]['sq']['current']
                ) {
                    $this->castlingAbility = $this->castlingRule->remove(
                        $this->castlingAbility,
                        $oppColor,
                        [Piece::K]
                    );
                } elseif (
                    $piece->move['sq']['next'] ===
                    $this->castlingRule->getRule()[$oppColor][Piece::R][Castle::LONG]['sq']['current']
                ) {
                    $this->castlingAbility = $this->castlingRule->remove(
                        $this->castlingAbility,
                        $oppColor,
                        [Piece::Q]
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Captures a piece.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Variant\Classical\Board
     */
    protected function capture(AbstractPiece $piece): Board
    {
        if (
            $piece->id === Piece::P &&
            $piece->getEnPassantSq() &&
            !$this->getPieceBySq($piece->move['sq']['next'])
        ) {
            if ($captured = $piece->enPassantPawn()) {
                $capturedData = (object) [
                    'id' => $captured->id,
                    'sq' => $captured->sq,
                ];
            }
        } elseif ($captured = $this->getPieceBySq($piece->move['sq']['next'])) {
            $capturedData = (object) [
                'id' => $captured->id,
                'sq' => $captured->sq,
            ];
        }
        if ($captured) {
            $capturingData = (object) [
                'id' => $piece->id,
                'sq' => $piece->sq,
            ];
            $piece->id !== Piece::R ?: $capturingData->type = $piece->getType();
            $captured->id !== Piece::R ?: $capturedData->type = $captured->getType();
            $capture = (object) [
                'capturing' => $capturingData,
                'captured' => $capturedData,
            ];
            $this->pushCapture($piece->color, $capture);
            $this->detach($captured);
        }

        return $this;
    }

    /**
     * Adds a new element to the captured pieces.
     *
     * @param string $color
     * @param object $capture
     * @return \Chess\Variant\Classical\Board
     */
    protected function pushCapture(string $color, object $capture): Board
    {
        $this->captures[$color][] = $capture;

        return $this;
    }

    /**
     * Promotes a pawn.
     *
     * @param \Chess\Piece\P $pawn
     * @return \Chess\Variant\Classical\Board
     */
    protected function promote(P $pawn): Board
    {
        $this->detach($this->getPieceBySq($pawn->move['sq']['next']));
        if ($pawn->move['newId'] === Piece::N) {
            $this->attach(new N(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square
            ));
        } elseif ($pawn->move['newId'] === Piece::B) {
            $this->attach(new B(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square
            ));
        } elseif ($pawn->move['newId'] === Piece::R) {
            $this->attach(new R(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square,
                RType::PROMOTED
            ));
        } else {
            $this->attach(new Q(
                $pawn->color,
                $pawn->move['sq']['next'],
                $this->square
            ));
        }

        return $this;
    }

    /**
     * Returns true if the current player is trapped.
     *
     * @return bool
     */
    protected function isTrapped(): bool
    {
        $escape = 0;
        foreach ($this->getPieces($this->turn) as $piece) {
            foreach ($piece->sqs() as $sq) {
                if ($piece->id === Piece::K) {
                    if ($sq === $piece->sqCastleShort()) {
                        $move = $this->move->toArray($this->turn, Castle::SHORT, $this->castlingRule, $this->color);
                    } elseif ($sq === $piece->sqCastleLong()) {
                        $move = $this->move->toArray($this->turn, CASTLE::LONG, $this->castlingRule, $this->color);
                    } elseif (in_array($sq, $this->sqCount->used->{$piece->oppColor()})) {
                        $move = $this->move->toArray($this->turn, Piece::K."x$sq", $this->castlingRule, $this->color);
                    } elseif (!in_array($sq, $this->spaceEval->{$piece->oppColor()})) {
                        $move = $this->move->toArray($this->turn, Piece::K.$sq, $this->castlingRule, $this->color);
                    }
                } elseif ($piece->id === Piece::P) {
                    if (in_array($sq, $this->sqCount->used->{$piece->oppColor()})) {
                        $move = $this->move->toArray($this->turn, $piece->file()."x$sq", $this->castlingRule, $this->color);
                    } else {
                        $move = $this->move->toArray($this->turn, $sq, $this->castlingRule, $this->color);
                    }
                } else {
                    if (in_array($sq, $this->sqCount->used->{$piece->oppColor()})) {
                        $move = $this->move->toArray($this->turn, $piece->id."x$sq", $this->castlingRule, $this->color);
                    } else {
                        $move = $this->move->toArray($this->turn, $piece->id.$sq, $this->castlingRule, $this->color);
                    }
                }
                $piece->move = $move;
                $escape += (int) !$this->isPinned($piece);
            }
        }

        return $escape === 0;
    }

    /**
     * Returns true if the piece is pinned.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return bool
     */
    protected function isPinned(AbstractPiece $piece): bool
    {
        $clone = $this->clone();
        if (
            $piece->move['type'] === $clone->move->case(Move::CASTLE_SHORT) &&
            $clone->castle($piece, RType::CASTLE_SHORT)
        ) {
            $king = $clone->getPiece($piece->color, Piece::K);
        } elseif (
            $piece->move['type'] === $clone->move->case(Move::CASTLE_LONG) &&
            $clone->castle($piece, RType::CASTLE_LONG)
        ) {
            $king = $clone->getPiece($piece->color, Piece::K);
        } else {
            $clone->move($piece);
            $king = $clone->getPiece($piece->color, Piece::K);
        }

        if ($king) {
            return !empty($king->attackingPieces());
        }

        throw new BoardException();
    }

    /**
     * Adds a new element to the history.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Variant\Classical\Board
     */
    protected function pushHistory(AbstractPiece $piece): Board
    {
        $this->history[] = [
            'castlingAbility' => $this->castlingAbility,
            'sq' => $piece->sq,
            'move' => $piece->move,
        ];

        return $this;
    }

    /**
     * Removes an element from the history.
     *
     * @return \Chess\Variant\Classical\Board
     */
    protected function popHistory(): Board
    {
        array_pop($this->history);

        return $this;
    }
}
