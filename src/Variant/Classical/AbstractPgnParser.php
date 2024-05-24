<?php

namespace Chess\Variant\Classical;

use Chess\Piece\AbstractPiece;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * AbstractPgnParser
 *
 * The root class in the hierarchy of chess boards defines the getter and the
 * setter methods, in addition to implementing the internal methods required to
 * convert a PGN move in text format into a data structure.
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
    protected string $turn = '';

    /**
     * Captured pieces.
     *
     * @var array
     */
    protected array $captures = [
        Color::W => [],
        Color::B => [],
    ];

    /**
     * History.
     *
     * @var array
     */
    protected array $history = [];

    /**
     * Castling rule.
     *
     * @var array
     */
    protected array $castlingRule = [];

    /**
     * Castling ability.
     *
     * @var string
     */
    protected string $castlingAbility = '';

    /**
     * Start FEN position.
     *
     * @var string
     */
    protected string $startFen = '';

    /**
     * Size.
     *
     * @var array
     */
    protected array $size;

    /**
     * Squares.
     *
     * @var array
     */
    protected array $sqs = [];

    /**
     * Move.
     *
     * @var \Chess\Variant\Classical\PGN\Move
     */
    protected Move $move;

    /**
     * Space evaluation.
     *
     * @var object
     */
    protected object $spaceEval;

    /**
     * Count squares.
     *
     * @var object
     */
    protected object $sqCount;

    /**
     * Returns the current turn.
     *
     * @return string
     */
    public function getTurn(): string
    {
        return $this->turn;
    }

    /**
     * Sets the current turn.
     *
     * @param string $color
     * @return \Chess\Variant\Classical\Board
     */
    public function setTurn(string $color): Board
    {
        $this->turn = Color::validate($color);

        return $this;
    }

    /**
     * Returns the pieces captured by both players.
     *
     * @return array|null
     */
    public function getCaptures(): ?array
    {
        return $this->captures;
    }

    /**
     * Returns the history.
     *
     * @return array|null
     */
    public function getHistory(): ?array
    {
        return $this->history;
    }

    /**
     * Returns the castling rule.
     *
     * @return array
     */
    public function getCastlingRule(): array
    {
        return $this->castlingRule;
    }

    /**
     * Returns the castling ability.
     *
     * @return string
     */
    public function getCastlingAbility(): string
    {
        return $this->castlingAbility;
    }

    /**
     * Returns the start FEN.
     *
     * @return string
     */
    public function getStartFen(): string
    {
        return $this->startFen;
    }

    /**
     * Sets the start FEN.
     *
     * @param string $fen
     * @return \Chess\Variant\Classical\Board
     */
    public function setStartFen(string $fen): Board
    {
        $this->startFen = $fen;

        return $this;
    }

    /**
     * Returns the size.
     *
     * @return array
     */
    public function getSize(): array
    {
        return $this->size;
    }

    /**
     * Returns the squares.
     *
     * @return array
     */
    public function getSqs(): array
    {
        return $this->sqs;
    }

    /**
     * Returns the move.
     *
     * @return \Chess\Variant\Classical\PGN\Move
     */
    public function getMove(): Move
    {
        return $this->move;
    }

    /**
     * Returns the space evaluation.
     *
     * @return object
     */
    public function getSpaceEval(): object
    {
        return $this->spaceEval;
    }

    /**
     * Returns the square evaluation.
     *
     * @return object
     */
    public function getSqCount(): object
    {
        return $this->sqCount;
    }

    /**
     * Picks a piece from the board.
     *
     * @param object $move
     * @return array
     */
    protected function pickPiece(object $move): array
    {
        $pieces = [];
        foreach ($this->getPieces($move->color) as $piece) {
            if ($piece->getId() === $move->id) {
                if (strstr($piece->getSq(), $move->sq->current)) {
                    $pieces[] = $piece->setMove($move);
                }
            }
        }

        return $pieces;
    }

    /**
     * Returns true if the move is syntactically valid.
     *
     * @param object $move
     * @return bool
     */
    protected function isValidMove(object $move): bool
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
     * @param object $move
     * @return bool
     */
    protected function isAmbiguousCapture(object $move): bool
    {
        if ($move->isCapture) {
            if ($move->id === Piece::P) {
                $enPassant = $this->history
                    ? $this->enPassant()
                    : explode(' ', $this->startFen)[3];
                if (!$this->getPieceBySq($move->sq->next) && $enPassant !== $move->sq->next) {
                    return true;
                }
            } else {
                if (!$this->getPieceBySq($move->sq->next)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if the move is ambiguous.
     *
     * @param object $move
     * @return bool
     */
    protected function isAmbiguousMove(object $move): bool
    {
        $ambiguous = [];
        foreach ($this->pickPiece($move) as $piece) {
            if (in_array($move->sq->next, $piece->sqs())) {
                if (!$this->isPinned($piece)) {
                    $ambiguous[] = $move->sq->next;
                }
            }
        }

        return count($ambiguous) > 1;
    }

    /**
     * Returns true if the move is legal.
     *
     * @param object $move
     * @return bool
     */
    protected function isLegalMove(object $move): bool
    {
        foreach ($pieces = $this->pickPiece($move) as $piece) {
            if ($piece->isMovable()) {
                if (!$this->isPinned($piece)) {
                    if ($piece->getMove()->type === $this->move->case(Move::CASTLE_SHORT)) {
                        return $this->castle($piece, RType::CASTLE_SHORT);
                    } elseif ($piece->getMove()->type === $this->move->case(Move::CASTLE_LONG)) {
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
        if ($piece->getMove()->isCapture) {
            $this->capture($piece);
        }
        if ($toDetach = $this->getPieceBySq($piece->getSq())) {
            $this->detach($toDetach);
        }
        $class = "\\Chess\\Piece\\{$piece->getId()}";
        $this->attach(new $class(
            $piece->getColor(),
            $piece->getMove()->sq->next,
            $this->size,
            $piece->getId() === Piece::R ? $piece->getType() : null
        ));
        if ($piece->getId() === Piece::P) {
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
            $this->detach($this->getPieceBySq($king->getSq()));
            $this->attach(
                new K(
                    $king->getColor(),
                    $this->castlingRule[$king->getColor()][Piece::K][rtrim($king->getMove()->pgn, '+')]['sq']['next'],
                    $this->size
                )
             );
            $this->detach($rook);
            $this->attach(
                new R(
                    $rook->getColor(),
                    $this->castlingRule[$king->getColor()][Piece::R][rtrim($king->getMove()->pgn, '+')]['sq']['next'],
                    $this->size,
                    $rook->getType()
                )
            );
            $this->castlingAbility = CastlingAbility::castle($this->castlingAbility, $this->turn);
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
        if (CastlingAbility::can($this->castlingAbility, $this->turn)) {
            if ($piece->getId() === Piece::K) {
                $this->castlingAbility = CastlingAbility::remove(
                    $this->castlingAbility,
                    $this->turn,
                    [Piece::K, Piece::Q]
                );
            } elseif ($piece->getId() === Piece::R) {
                if ($piece->getType() === RType::CASTLE_SHORT) {
                    $this->castlingAbility = CastlingAbility::remove(
                        $this->castlingAbility,
                        $this->turn,
                        [Piece::K]
                    );
                } elseif ($piece->getType() === RType::CASTLE_LONG) {
                    $this->castlingAbility = CastlingAbility::remove(
                        $this->castlingAbility,
                        $this->turn,
                        [Piece::Q]
                    );
                }
            }
        }
        $oppColor = Color::opp($this->turn);
        if (CastlingAbility::can($this->castlingAbility, $oppColor)) {
            if ($piece->getMove()->isCapture) {
                if ($piece->getMove()->sq->next ===
                    $this->castlingRule[$oppColor][Piece::R][Castle::SHORT]['sq']['current']
                ) {
                    $this->castlingAbility = CastlingAbility::remove(
                        $this->castlingAbility,
                        $oppColor,
                        [Piece::K]
                    );
                } elseif (
                    $piece->getMove()->sq->next ===
                    $this->castlingRule[$oppColor][Piece::R][Castle::LONG]['sq']['current']
                ) {
                    $this->castlingAbility = CastlingAbility::remove(
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
            $piece->getId() === Piece::P &&
            $piece->getEnPassantSq() &&
            !$this->getPieceBySq($piece->getMove()->sq->next)
        ) {
            if ($captured = $piece->enPassantPawn()) {
                $capturedData = (object) [
                    'id' => $captured->getId(),
                    'sq' => $captured->getSq(),
                ];
            }
        } elseif ($captured = $this->getPieceBySq($piece->getMove()->sq->next)) {
            $capturedData = (object) [
                'id' => $captured->getId(),
                'sq' => $captured->getSq(),
            ];
        }
        if ($captured) {
            $capturingData = (object) [
                'id' => $piece->getId(),
                'sq' => $piece->getSq(),
            ];
            $piece->getId() !== Piece::R ?: $capturingData->type = $piece->getType();
            $captured->getId() !== Piece::R ?: $capturedData->type = $captured->getType();
            $capture = (object) [
                'capturing' => $capturingData,
                'captured' => $capturedData,
            ];
            $this->pushCapture($piece->getColor(), $capture);
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
        $this->detach($this->getPieceBySq($pawn->getMove()->sq->next));
        if ($pawn->getMove()->newId === Piece::N) {
            $this->attach(new N(
                $pawn->getColor(),
                $pawn->getMove()->sq->next,
                $this->size
            ));
        } elseif ($pawn->getMove()->newId === Piece::B) {
            $this->attach(new B(
                $pawn->getColor(),
                $pawn->getMove()->sq->next,
                $this->size
            ));
        } elseif ($pawn->getMove()->newId === Piece::R) {
            $this->attach(new R(
                $pawn->getColor(),
                $pawn->getMove()->sq->next,
                $this->size,
                RType::PROMOTED
            ));
        } else {
            $this->attach(new Q(
                $pawn->getColor(),
                $pawn->getMove()->sq->next,
                $this->size
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
                if ($piece->getId() === Piece::K) {
                    if ($sq === $piece->sqCastleShort()) {
                        $move = $this->move->toObj($this->turn, Castle::SHORT, $this->castlingRule);
                    } elseif ($sq === $piece->sqCastleLong()) {
                        $move = $this->move->toObj($this->turn, CASTLE::LONG, $this->castlingRule);
                    } elseif (in_array($sq, $this->sqCount->used->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, Piece::K."x$sq", $this->castlingRule);
                    } elseif (!in_array($sq, $this->spaceEval->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, Piece::K.$sq, $this->castlingRule);
                    }
                } elseif ($piece->getId() === Piece::P) {
                    if (in_array($sq, $this->sqCount->used->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, $piece->getSqFile()."x$sq", $this->castlingRule);
                    } else {
                        $move = $this->move->toObj($this->turn, $sq, $this->castlingRule);
                    }
                } else {
                    if (in_array($sq, $this->sqCount->used->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, $piece->getId()."x$sq", $this->castlingRule);
                    } else {
                        $move = $this->move->toObj($this->turn, $piece->getId().$sq, $this->castlingRule);
                    }
                }
                $escape += (int) !$this->isPinned($piece->setMove($move));
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
        $clone = unserialize(serialize($this));
        if (
            $piece->getMove()->type === $clone->move->case(Move::CASTLE_SHORT) &&
            $clone->castle($piece, RType::CASTLE_SHORT)
        ) {
            $king = $clone->getPiece($piece->getColor(), Piece::K);
        } elseif (
            $piece->getMove()->type === $clone->move->case(Move::CASTLE_LONG) &&
            $clone->castle($piece, RType::CASTLE_LONG)
        ) {
            $king = $clone->getPiece($piece->getColor(), Piece::K);
        } else {
            $clone->move($piece);
            $king = $clone->getPiece($piece->getColor(), Piece::K);
        }

        return !empty($king->attackingPieces());
    }

    /**
     * Adds a new element to the history.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Variant\Classical\Board
     */
    protected function pushHistory(AbstractPiece $piece): Board
    {
        $this->history[] = (object) [
            'castlingAbility' => $this->castlingAbility,
            'sq' => $piece->getSq(),
            'move' => $piece->getMove(),
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
