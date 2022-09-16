<?php

namespace Chess;

use Chess\Array\AsciiArray;
use Chess\Eval\DefenseEval;
use Chess\Eval\PressureEval;
use Chess\Eval\SpaceEval;
use Chess\Eval\SqEval;
use Chess\Exception\BoardException;
use Chess\FEN\BoardToStr;
use Chess\FEN\Field\CastlingAbility;
use Chess\PGN\Move;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Piece\AbstractPiece;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;

/**
 * Board
 *
 * Chess board representation that allows to play a game of chess in Portable
 * Game Notation (PGN) format. This class is the cornerstone that allows to build
 * multiple features on top of it: FEN string generation, ASCII representation,
 * PNG image creation, position evaluation, etc.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Board extends \SplObjectStorage
{
    use BoardObserverPieceTrait;

    /**
     * Current player's turn.
     *
     * @var string
     */
    private string $turn = '';

    /**
     * Captured pieces.
     *
     * @var array
     */
    private array $captures = [
        Color::W => [],
        Color::B => [],
    ];

    /**
     * History.
     *
     * @var array
     */
    private array $history = [];

    /**
     * Castling ability.
     *
     * @var array
     */
    private string $castlingAbility;

    /**
     * Observers.
     *
     * @var array
     */
    private array $observers;

    /**
     * Defense evaluation.
     *
     * @var object
     */
    private object $defenseEval;

    /**
     * Pressure evaluation.
     *
     * @var object
     */
    private object $pressureEval;

    /**
     * Space evaluation.
     *
     * @var object
     */
    private object $spaceEval;

    /**
     * Square evaluation.
     *
     * @var object
     */
    private object $sqEval;

    /**
     * Constructor.
     *
     * @param array $pieces
     * @param string $castlingAbility
     */
    public function __construct(array $pieces = null, string $castlingAbility = '-')
    {
        if (!$pieces) {
            $this->attach(new R(Color::W, 'a1', RType::CASTLE_LONG));
            $this->attach(new N(Color::W, 'b1'));
            $this->attach(new B(Color::W, 'c1'));
            $this->attach(new Q(Color::W, 'd1'));
            $this->attach(new K(Color::W, 'e1'));
            $this->attach(new B(Color::W, 'f1'));
            $this->attach(new N(Color::W, 'g1'));
            $this->attach(new R(Color::W, 'h1', RType::CASTLE_SHORT));
            $this->attach(new P(Color::W, 'a2'));
            $this->attach(new P(Color::W, 'b2'));
            $this->attach(new P(Color::W, 'c2'));
            $this->attach(new P(Color::W, 'd2'));
            $this->attach(new P(Color::W, 'e2'));
            $this->attach(new P(Color::W, 'f2'));
            $this->attach(new P(Color::W, 'g2'));
            $this->attach(new P(Color::W, 'h2'));
            $this->attach(new R(Color::B, 'a8', RType::CASTLE_LONG));
            $this->attach(new N(Color::B, 'b8'));
            $this->attach(new B(Color::B, 'c8'));
            $this->attach(new Q(Color::B, 'd8'));
            $this->attach(new K(Color::B, 'e8'));
            $this->attach(new B(Color::B, 'f8'));
            $this->attach(new N(Color::B, 'g8'));
            $this->attach(new R(Color::B, 'h8', RType::CASTLE_SHORT));
            $this->attach(new P(Color::B, 'a7'));
            $this->attach(new P(Color::B, 'b7'));
            $this->attach(new P(Color::B, 'c7'));
            $this->attach(new P(Color::B, 'd7'));
            $this->attach(new P(Color::B, 'e7'));
            $this->attach(new P(Color::B, 'f7'));
            $this->attach(new P(Color::B, 'g7'));
            $this->attach(new P(Color::B, 'h7'));
            $this->castlingAbility = CastlingAbility::START;
        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }
            $this->castlingAbility = $castlingAbility;
        }

        $this->refresh();
    }

    /**
     * Gets the current turn.
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
     * @return \Chess\Board
     */
    public function setTurn(string $color): Board
    {
        $this->turn = Color::validate($color);

        return $this;
    }

    /**
     * Gets the square evaluation.
     *
     * @return object
     */
    public function getSqEval(): object
    {
        return $this->sqEval;
    }

    /**
     * Gets the space evaluation.
     *
     * @return object
     */
    public function getSpaceEval(): object
    {
        return $this->spaceEval;
    }

    /**
     * Gets the defense evaluation.
     *
     * @return object
     */
    public function getDefenseEval(): object
    {
        return $this->defenseEval;
    }

    /**
     * Gets the castling ability.
     *
     * @return string
     */
    public function getCastlingAbility(): string
    {
        return $this->castlingAbility;
    }

    /**
     * Gets the pieces captured by both players.
     *
     * @return mixed array|null
     */
    public function getCaptures(): ?array
    {
        return $this->captures;
    }

    /**
     * Adds a new element to the captured pieces.
     *
     * @param string $color
     * @param object $capture
     * @return \Chess\Board
     */
    private function pushCapture(string $color, object $capture): Board
    {
        $this->captures[$color][] = $capture;

        return $this;
    }

    /**
     * Removes an element from the captured pieces.
     *
     * @param string $color
     * @return \Chess\Board
     */
    private function popCapture(string $color): Board
    {
        array_pop($this->captures[$color]);

        return $this;
    }

    /**
     * Gets the history.
     *
     * @return mixed array|null
     */
    public function getHistory(): ?array
    {
        return $this->history;
    }

    /**
     * Gets the movetext.
     *
     * @return string
     */
    public function getMovetext(): string
    {
        $movetext = '';
        foreach ($this->history as $key => $val) {
            $key % 2 === 0
                ? $movetext .= (($key / 2) + 1) . ".{$val->move->pgn}"
                : $movetext .= " {$val->move->pgn} ";
        }

        return trim($movetext);
    }

    /**
     * Adds a new element to the history.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Board
     */
    private function pushHistory(AbstractPiece $piece): Board
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
     * @return \Chess\Board
     */
    private function popHistory(): Board
    {
        array_pop($this->history);

        return $this;
    }

    /**
     * Gets the first piece on the board matching the search criteria.
     *
     * @param string $color
     * @param string $id
     * @return mixed \Chess\Piece\AbstractPiece|null
     */
    public function getPiece(string $color, string $id): ?AbstractPiece
    {
        $this->rewind();
        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->getColor() === $color && $piece->getId() === $id) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

    /**
     * Gets the pieces by color.
     *
     * @param string $color
     * @return array
     */
    public function getPiecesByColor(string $color): array
    {
        $pieces = [];
        $this->rewind();
        while ($this->valid()) {
            $piece = $this->current();
            $piece->getColor() !== $color ?: $pieces[] = $piece;
            $this->next();
        }

        return $pieces;
    }

    /**
     * Gets all pieces.
     *
     * @return array
     */
    public function getPieces(): array
    {
        $pieces = [];
        $this->rewind();
        while ($this->valid()) {
            $pieces[] = $this->current();
            $this->next();
        }

        return $pieces;
    }

    /**
     * Gets a piece by its position on the board.
     *
     * @param string $sq
     * @return mixed \Chess\Piece\AbstractPiece|null
     */
    public function getPieceBySq(string $sq): ?AbstractPiece
    {
        $this->rewind();
        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->getSq() === $sq) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

    /**
     * Picks a piece to be moved.
     *
     * @param object $move
     * @return array
     * @throws \Chess\Exception\BoardException
     */
    private function pickPiece(object $move): array
    {
        $found = [];
        foreach ($this->getPiecesByColor($move->color) as $piece) {
            if ($piece->getId() === $move->id) {
                if ($piece->getId() === Piece::K) {
                    return [$piece->setMove($move)];
                } elseif (preg_match("/{$move->sq->current}/", $piece->getSq())) {
                    $found[] = $piece->setMove($move);
                }
            }
        }
        if (!$found) {
            throw new BoardException;
        }

        return $found;
    }

    /**
     * Captures a piece.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Board
     */
    private function capture(AbstractPiece $piece): Board
    {
        $piece->sqs(); // creates the enPassantSquare property if the piece is a pawn
        if (
            $piece->getId() === Piece::P &&
            $piece->getEnPassantSq() &&
            !$this->getPieceBySq($piece->getMove()->sq->next)
        ) {
            if ($captured = $this->getPieceBySq($piece->getEnPassantSq())) {
                $capturedData = (object) [
                    'id' => $captured->getId(),
                    'sq' => $piece->getEnPassantSq(),
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
     * Promotes a pawn.
     *
     * @param \Chess\Piece\P $pawn
     * @return \Chess\Board
     */
    private function promote(P $pawn): Board
    {
        $this->detach($this->getPieceBySq($pawn->getMove()->sq->next));
        switch ($pawn->getMove()->newId) {
            case Piece::N:
                $this->attach(new N($pawn->getColor(), $pawn->getMove()->sq->next));
                break;
            case Piece::B:
                $this->attach(new B($pawn->getColor(), $pawn->getMove()->sq->next));
                break;
            case Piece::R:
                $this->attach(new R($pawn->getColor(), $pawn->getMove()->sq->next, RType::PROMOTED));
                break;
            default:
                $this->attach(new Q($pawn->getColor(), $pawn->getMove()->sq->next));
                break;
        }

        return $this;
    }

    /**
     * Checks out if a chess move is valid.
     *
     * @param object $move
     * @return bool true if the move is valid; otherwise false
     */
    private function isValidMove(object $move): bool
    {
        if ($move->color !== $this->turn) {
            return false;
        } elseif (
            $move->isCapture &&
            $move->id !== Piece::P &&
            !$this->getPieceBySq($move->sq->next)
        ) {
            return false;
        } elseif (!$move->isCapture && $this->getPieceBySq($move->sq->next)) {
            return false;
        }

        return true;
    }

    /**
     * Checks out if a chess move is legal.
     *
     * @param object $move
     * @return bool true if the move is legal; otherwise false
     */
    private function isLegalMove(object $move): bool
    {
        $pieces = $this->pickPiece($move);
        if (count($pieces) > 1) {
            foreach ($pieces as $piece) {
                if ($piece->isMovable() && !$this->leavesInCheck($piece)) {
                    return $this->move($piece);
                }
            }
        } elseif ($piece = $pieces[0]) {
            if ($piece->isMovable() && !$this->leavesInCheck($piece)) {
                if (
                  $piece->getMove()->type === Move::CASTLE_SHORT &&
                  $piece->sqCastleShort()
                ) {
                    return $this->castle($piece);
                } elseif (
                  $piece->getMove()->type === Move::CASTLE_LONG &&
                  $piece->sqCastleLong()
                ) {
                    return $this->castle($piece);
                } else {
                    return $this->move($piece);
                }
            }
         }

        return false;
    }

    /**
     * Makes a move.
     *
     * @param string $color
     * @param string $pgn
     * @return bool true if the move can be made; otherwise false
     */
    public function play(string $color, string $pgn): bool
    {
        $obj = Move::toObj($color, $pgn);

        return $this->isValidMove($obj) && $this->isLegalMove($obj);
    }

    /**
     * Castles the king.
     *
     * @param \Chess\Piece\K $king
     * @return bool true if the castle move can be made; otherwise false
     */
    private function castle(K $king): bool
    {
        if ($rook = $king->getCastleRook(iterator_to_array($this, false))) {
            $this->detach($this->getPieceBySq($king->getSq()));
            $this->attach(
                new K(
                    $king->getColor(),
                    K::$castlingRule[$king->getColor()][Piece::K][rtrim($king->getMove()->pgn, '+')]['sq']['next']
                )
             );
            $this->detach($rook);
            $this->attach(
                new R(
                    $rook->getColor(),
                    K::$castlingRule[$king->getColor()][Piece::R][rtrim($king->getMove()->pgn, '+')]['sq']['next'],
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
     * Undoes a castle move.
     *
     * @param string $sq
     * @param object $move
     * @return \Chess\Board
     */
    private function undoCastle(string $sq, object $move): Board
    {
        $king = $this->getPieceBySq($move->sq->next);
        $kingUndone = new K($move->color, $sq);
        $this->detach($king);
        $this->attach($kingUndone);
        if (Move::CASTLE_SHORT === $move->type) {
            $rook = $this->getPieceBySq(
                K::$castlingRule[$move->color][Piece::R][Castle::SHORT]['sq']['next']
            );
            $rookUndone = new R(
                $move->color,
                K::$castlingRule[$move->color][Piece::R][Castle::SHORT]['sq']['current'],
                $rook->getType()
            );
            $this->detach($rook);
            $this->attach($rookUndone);
        } elseif (Move::CASTLE_LONG === $move->type) {
            $rook = $this->getPieceBySq(
                K::$castlingRule[$move->color][Piece::R][Castle::LONG]['sq']['next']
            );
            $rookUndone = new R(
                $move->color,
                K::$castlingRule[$move->color][Piece::R][Castle::LONG]['sq']['current'],
                $rook->getType()
            );
            $this->detach($rook);
            $this->attach($rookUndone);
        }
        $this->popHistory()->refresh();

        return $this;
    }

    /**
     * Updates the castle property.
     *
     * @param \Chess\Piece\AbstractPiece $pieceMoved
     * @return \Chess\Board
     */
    private function updateCastle(AbstractPiece $pieceMoved): Board
    {
        if (CastlingAbility::can($this->castlingAbility, $this->turn)) {
            if ($pieceMoved->getId() === Piece::K) {
                $this->castlingAbility = CastlingAbility::remove(
                    $this->castlingAbility,
                    $this->turn,
                    [Piece::K, Piece::Q]
                );
            } elseif ($pieceMoved->getId() === Piece::R) {
                if ($pieceMoved->getType() === RType::CASTLE_SHORT) {
                    $this->castlingAbility = CastlingAbility::remove(
                        $this->castlingAbility,
                        $this->turn,
                        [Piece::K]
                    );
                } elseif ($pieceMoved->getType() === RType::CASTLE_LONG) {
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
            if ($pieceMoved->getMove()->isCapture) {
                if ($pieceMoved->getMove()->sq->next ===
                    K::$castlingRule[$oppColor][Piece::R][Castle::SHORT]['sq']['current']
                ) {
                    $this->castlingAbility = CastlingAbility::remove(
                        $this->castlingAbility,
                        $oppColor,
                        [Piece::K]
                    );
                } elseif (
                    $pieceMoved->getMove()->sq->next ===
                    K::$castlingRule[$oppColor][Piece::R][Castle::LONG]['sq']['current']
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
     * Moves a piece.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return bool true if the move can be made; otherwise false
     */
    private function move(AbstractPiece $piece): bool
    {
        if ($piece->getMove()->isCapture) {
            $this->capture($piece);
        }
        $this->detach($this->getPieceBySq($piece->getSq()));
        $className = "\\Chess\\Piece\\{$piece->getId()}";
        $this->attach(new $className(
            $piece->getColor(),
            $piece->getMove()->sq->next,
            $piece->getId() !== Piece::R ?: $piece->getType()
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
     * Undoes the last move.
     *
     * @param string $sq
     * @param object $move
     * @return \Chess\Board
     */
    private function undoMove(string $sq, object $move): Board
    {
        $piece = $this->getPieceBySq($move->sq->next);
        $this->detach($piece);
        if (
            $move->type === Move::PAWN_PROMOTES ||
            $move->type === Move::PAWN_CAPTURES_AND_PROMOTES
        ) {
            $pieceUndone = new P($move->color, $sq);
            $this->attach($pieceUndone);
        } else {
            $className = "\\Chess\\Piece\\{$piece->getId()}";
            $pieceUndone = new $className(
                $move->color,
                $sq,
                $piece->getId() !== Piece::R ?: $piece->getType()
            );
            $this->attach($pieceUndone);
        }
        if ($move->isCapture && $capture = end($this->captures[$move->color])) {
            $className = "\\Chess\\Piece\\{$capture->captured->id}";
            $this->attach(new $className(
                Color::opp($move->color),
                $capture->captured->sq,
                $capture->captured->id !== Piece::R ?: $capture->captured->type
            ));
            $this->popCapture($move->color);
        }
        $this->popHistory()->refresh();

        return $this;
    }

    /**
     * Undoes the last move.
     *
     * @return \Chess\Board
     */
    public function undo(): Board
    {
        if ($last = end($this->history)) {
            if (
                $last->move->type === Move::CASTLE_SHORT ||
                $last->move->type === Move::CASTLE_LONG
            ) {
                $this->undoCastle($last->sq, $last->move);
                $nextToLast = end($this->history);
                $this->castlingAbility = $nextToLast->castlingAbility;
            } elseif (
                $last->move->type === Move::KING ||
                $last->move->type === Move::KING_CAPTURES
            ) {
                $this->undoMove($last->sq, $last->move);
                $nextToLast = end($this->history);
                $this->castlingAbility = $nextToLast->castlingAbility;
            } else {
                $this->undoMove($last->sq, $last->move);
                $this->castlingAbility = $last->castlingAbility;
            }
        }

        return $this;
    }

    /**
     * Refreshes the state of the board.
     */
    public function refresh(): void
    {
        $this->turn = Color::opp($this->turn);

        $this->sqEval = (object) [
            SqEval::TYPE_FREE => (new SqEval($this))->eval(SqEval::TYPE_FREE),
            SqEval::TYPE_USED => (object) (new SqEval($this))->eval(SqEval::TYPE_USED),
        ];

        $this->detachPieces()
            ->attachPieces()
            ->notifyPieces();

        $this->spaceEval = (object) (new SpaceEval($this))->eval();
        $this->pressureEval = (object) (new PressureEval($this))->eval();
        $this->defenseEval = (object) (new DefenseEval($this))->eval();

        $this->notifyPieces();
    }

    /**
     * Checks out if the board is in check when a piece is moved.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return bool
     */
    private function leavesInCheck(AbstractPiece $piece): bool
    {
        $lastCastlingAbility = $this->castlingAbility;
        $this->move($piece);
        $king = $this->getPiece($piece->getColor(), Piece::K);
        $leavesInCheck = in_array($king->getSq(), $this->pressureEval->{$king->oppColor()});
        $this->undoMove($piece->getSq(), $piece->getMove());
        $this->castlingAbility = $lastCastlingAbility;

        return $leavesInCheck;
    }

    /**
     * Checks out whether the current player is trapped.
     *
     * @return bool
     */
    private function isTrapped(): bool
    {
        $escape = 0;
        foreach ($this->getPiecesByColor($this->turn) as $piece) {
            foreach ($piece->sqs() as $sq) {
                switch ($piece->getId()) {
                    case Piece::K:
                        if (in_array($sq, $this->sqEval->used->{$piece->oppColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(Move::toObj($this->turn, Piece::K."x$sq"))
                            );
                        } elseif (!in_array($sq, $this->spaceEval->{$piece->oppColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(Move::toObj($this->turn, Piece::K.$sq))
                            );
                        }
                        break;
                    case Piece::P:
                        if (in_array($sq, $this->sqEval->used->{$piece->oppColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(Move::toObj($this->turn, $piece->getFile()."x$sq"))
                            );
                        } else {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(Move::toObj($this->turn, $sq))
                            );
                        }
                        break;
                    default:
                        if (in_array($sq, $this->sqEval->used->{$piece->oppColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(Move::toObj($this->turn, $piece->getId()."x$sq"))
                            );
                        } else {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(Move::toObj($this->turn, $piece->getId().$sq))
                            );
                        }
                        break;
                }
            }
        }

        return $escape === 0;
    }

    /**
     * Checks out whether the current player is in check.
     *
     * @return bool
     */
    public function isCheck(): bool
    {
        $king = $this->getPiece($this->turn, Piece::K);

        return in_array(
            $king->getSq(),
            $this->pressureEval->{$king->oppColor()}
        );
    }

    /**
     * Checks out whether the current player is checkmated.
     *
     * @return bool
     */
    public function isMate(): bool
    {
        return $this->isTrapped() && $this->isCheck();
    }

    /**
     * Checks out whether the current player is stalemated.
     *
     * @return bool
     */
    public function isStalemate(): bool
    {
        return $this->isTrapped() && !$this->isCheck();
    }

    /**
     * Returns the legal moves.
     *
     * @return array
     */
    public function legalMoves(): ?array
    {
        $moves = [];
        $color = $this->getTurn();
        foreach ($this->getPiecesByColor($color) as $piece) {
            foreach ($piece->sqs() as $sq) {
                $clone = unserialize(serialize($this));
                switch ($piece->getId()) {
                    case Piece::K:
                        if (
                            K::$castlingRule[$color][Piece::K][Castle::SHORT]['sq']['next'] === $sq &&
                            $piece->sqCastleShort() &&
                            $clone->play($color, Castle::SHORT)
                        ) {
                            $moves[] = Castle::SHORT;
                        } elseif (
                            K::$castlingRule[$color][Piece::K][Castle::LONG]['sq']['next'] === $sq &&
                            $piece->sqCastleLong() &&
                            $clone->play($color, Castle::LONG)
                        ) {
                            $moves[] = Castle::LONG;
                        } elseif ($clone->play($color, Piece::K.$sq)) {
                            $moves[] = Piece::K.$sq;
                        } elseif ($clone->play($color, Piece::K.'x'.$sq)) {
                            $moves[] = Piece::K.'x'.$sq;
                        }
                        break;
                    case Piece::P:
                        if ($clone->play($color, $sq)) {
                            $moves[] = $sq;
                        } elseif ($clone->play($color, $piece->getFile()."x$sq")) {
                            $moves[] = $piece->getFile()."x$sq";
                        }
                        break;
                    default:
                        if ($clone->play($color, $piece->getId().$sq)) {
                            $moves[] = $piece->getId().$sq;
                        } elseif ($clone->play($color, "{$piece->getId()}x$sq")) {
                            $moves[] = "{$piece->getId()}x$sq";
                        }
                        break;
                }
            }
        }

        return $moves;
    }

    /**
     * Returns the legal squares of a piece.
     *
     * @param string $sq
     * @return mixed null|object
     */
    public function legalSqs(string $sq): ?object
    {
        if ($piece = $this->getPieceBySq($sq)) {
            $sqs = [];
            $color = $piece->getColor();
            foreach ($piece->sqs() as $sq) {
                $clone = unserialize(serialize($this));
                switch ($piece->getId()) {
                    case Piece::K:
                        if ($clone->play($color, Piece::K.$sq)) {
                            $sqs[] = $sq;
                        } elseif ($clone->play($color, Piece::K.'x'.$sq)) {
                            $sqs[] = $sq;
                        }
                        break;
                    case Piece::P:
                        if ($clone->play($color, $piece->getFile()."x$sq")) {
                            $sqs[] = $sq;
                        } elseif ($clone->play($color, $sq)) {
                            $sqs[] = $sq;
                        }
                        break;
                    default:
                        if ($clone->play($color, $piece->getId().$sq)) {
                            $sqs[] = $sq;
                        } elseif ($clone->play($color, "{$piece->getId()}x$sq")) {
                            $sqs[] = $sq;
                        }
                        break;
                }
            }
            $result = [
                'color' => $color,
                'id' => $piece->getId(),
                'sqs' => $sqs,
            ];
            if ($piece->getId() === Piece::P) {
                if ($enPassant = $piece->getEnPassantSq()) {
                    $result['enPassant'] = $enPassant;
                }
            }
            return (object) $result;
        }

        return null;
    }

    /**
     * Returns an ASCII array representing this Chess\Board object.
     *
     * @param bool $flip
     * @return array
     */
    public function toAsciiArray(bool $flip = false): array
    {
        $array = [
            7 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            6 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            5 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            4 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            3 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            2 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            1 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            0 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
        ];
        foreach ($this->getPieces() as $piece) {
            $sq = $piece->getSq();
            list($file, $rank) = AsciiArray::fromAlgebraicToIndex($sq);
            if ($flip) {
                $file = 7 - $file;
                $rank = 7 - $rank;
            }
            $piece->getColor() === Color::W
                ? $array[$file][$rank] = ' ' . $piece->getId() . ' '
                : $array[$file][$rank] = ' ' . strtolower($piece->getId()) . ' ';
        }

        return $array;
    }

    /**
     * Returns an ASCII string representing this Chess\Board object.
     *
     * @param bool $flip
     * @return string
     */
    public function toAsciiString(bool $flip = false): string
    {
        $ascii = '';
        $array = $this->toAsciiArray($flip);
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $file) {
                $ascii .= $array[$i][$j];
            }
            $ascii .= PHP_EOL;
        }

        return $ascii;
    }

    /**
     * Returns a FEN representing this Chess\Board object.
     *
     * @return string
     */
    public function toFen(): string
    {
        return (new BoardToStr($this))->create();
    }

    /**
     * Returns the checking pieces.
     *
     * @return array
     */
    public function checkingPieces(): array
    {
        $pieces = [];
        foreach ($this->getPieces() as $piece) {
            if ($piece->isAttackingKing()) {
                $pieces[] = $piece;
            }
        }

        return $pieces;
    }
}
