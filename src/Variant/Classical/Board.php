<?php

namespace Chess\Variant\Classical;

use Chess\Piece\AsciiArray;
use Chess\Eval\DefenseEval;
use Chess\Eval\PressureEval;
use Chess\Eval\SpaceEval;
use Chess\Eval\SqEval;
use Chess\Exception\BoardException;
use Chess\Piece\AbstractPiece;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Classical\FEN\BoardToStr;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

/**
 * Board
 *
 * Chess board representation to play classical chess.
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
     * Size.
     *
     * @var array
     */
    protected array $size;

    /**
     * Move.
     *
     * @var \Chess\Variant\Classical\PGN\Move
     */
    protected Move $move;

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
        $this->size = Square::SIZE;
        $this->castlingAbility = CastlingAbility::START;
        $this->castlingRule = (new CastlingRule())->getRule();
        $this->move = new Move();
        if (!$pieces) {
            $this->attach(new R(Color::W, 'a1', $this->size, RType::CASTLE_LONG));
            $this->attach(new N(Color::W, 'b1', $this->size));
            $this->attach(new B(Color::W, 'c1', $this->size));
            $this->attach(new Q(Color::W, 'd1', $this->size));
            $this->attach(new K(Color::W, 'e1', $this->size));
            $this->attach(new B(Color::W, 'f1', $this->size));
            $this->attach(new N(Color::W, 'g1', $this->size));
            $this->attach(new R(Color::W, 'h1', $this->size, RType::CASTLE_SHORT));
            $this->attach(new P(Color::W, 'a2', $this->size));
            $this->attach(new P(Color::W, 'b2', $this->size));
            $this->attach(new P(Color::W, 'c2', $this->size));
            $this->attach(new P(Color::W, 'd2', $this->size));
            $this->attach(new P(Color::W, 'e2', $this->size));
            $this->attach(new P(Color::W, 'f2', $this->size));
            $this->attach(new P(Color::W, 'g2', $this->size));
            $this->attach(new P(Color::W, 'h2', $this->size));
            $this->attach(new R(Color::B, 'a8', $this->size, RType::CASTLE_LONG));
            $this->attach(new N(Color::B, 'b8', $this->size));
            $this->attach(new B(Color::B, 'c8', $this->size));
            $this->attach(new Q(Color::B, 'd8', $this->size));
            $this->attach(new K(Color::B, 'e8', $this->size));
            $this->attach(new B(Color::B, 'f8', $this->size));
            $this->attach(new N(Color::B, 'g8', $this->size));
            $this->attach(new R(Color::B, 'h8', $this->size, RType::CASTLE_SHORT));
            $this->attach(new P(Color::B, 'a7', $this->size));
            $this->attach(new P(Color::B, 'b7', $this->size));
            $this->attach(new P(Color::B, 'c7', $this->size));
            $this->attach(new P(Color::B, 'd7', $this->size));
            $this->attach(new P(Color::B, 'e7', $this->size));
            $this->attach(new P(Color::B, 'f7', $this->size));
            $this->attach(new P(Color::B, 'g7', $this->size));
            $this->attach(new P(Color::B, 'h7', $this->size));
        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }
            $this->castlingAbility = $castlingAbility;
        }

        $this->refresh();
    }

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
     * Returns the square evaluation.
     *
     * @return object
     */
    public function getSqEval(): object
    {
        return $this->sqEval;
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
     * Returns the defense evaluation.
     *
     * @return object
     */
    public function getDefenseEval(): object
    {
        return $this->defenseEval;
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
     * Returns the size.
     *
     * @return array
     */
    public function getSize(): array
    {
        return $this->size;
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
     * Adds a new element to the captured pieces.
     *
     * @param string $color
     * @param object $capture
     * @return \Chess\Variant\Classical\Board
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
     * @return \Chess\Variant\Classical\Board
     */
    private function popCapture(string $color): Board
    {
        array_pop($this->captures[$color]);

        return $this;
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
     * Returns the movetext.
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
     * @return \Chess\Variant\Classical\Board
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
     * @return \Chess\Variant\Classical\Board
     */
    private function popHistory(): Board
    {
        array_pop($this->history);

        return $this;
    }

    /**
     * Returns the first piece on the board matching the search criteria.
     *
     * @param string $color
     * @param string $id
     * @return AbstractPiece|null \Chess\Piece\AbstractPiece|null
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
     * Returns an array of pieces.
     *
     * @param string $color
     * @return array
     */
    public function getPieces(string $color = ''): array
    {
        $pieces = [];
        $this->rewind();
        while ($this->valid()) {
            $piece = $this->current();
            if ($color) {
                if ($piece->getColor() === $color) {
                    $pieces[] = $piece;
                }
            } else {
                $pieces[] = $piece;
            }
            $this->next();
        }

        return $pieces;
    }

    /**
     * Returns a piece by its position on the board.
     *
     * @param string $sq
     * @return AbstractPiece|null \Chess\Piece\AbstractPiece|null
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
        foreach ($this->getPieces($move->color) as $piece) {
            if ($piece->getId() === $move->id) {
                if ($piece->getId() === Piece::K) {
                    return [$piece->setMove($move)];
                } elseif (preg_match("/{$move->sq->current}/", $piece->getSq())) {
                    $found[] = $piece->setMove($move);
                }
            }
        }
        if (!$found) {
            throw new BoardException();
        }

        return $found;
    }

    /**
     * Captures a piece.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Variant\Classical\Board
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
     * @return \Chess\Variant\Classical\Board
     */
    private function promote(P $pawn): Board
    {
        $this->detach($this->getPieceBySq($pawn->getMove()->sq->next));
        switch ($pawn->getMove()->newId) {
            case Piece::N:
                $this->attach(new N(
                    $pawn->getColor(),
                    $pawn->getMove()->sq->next,
                    $this->size
                ));
                break;
            case Piece::B:
                $this->attach(new B(
                    $pawn->getColor(),
                    $pawn->getMove()->sq->next,
                    $this->size
                ));
                break;
            case Piece::R:
                $this->attach(new R(
                    $pawn->getColor(),
                    $pawn->getMove()->sq->next,
                    $this->size,
                    RType::PROMOTED
                ));
                break;
            default:
                $this->attach(new Q(
                    $pawn->getColor(),
                    $pawn->getMove()->sq->next,
                    $this->size
                ));
                break;
        }

        return $this;
    }

    /**
     * Checks out if a move is syntactically valid.
     *
     * @param object $move
     * @return bool true if the move is valid; otherwise false
     */
    protected function isValidMove(object $move): bool
    {
        return !$this->isAmbiguousMove($move) &&
            !$this->isAmbiguousCapture($move);
    }

    /**
     * Checks out if a move is ambiguous.
     *
     * @param object $move
     * @return bool true if the move is not ambiguous; otherwise false
     */
    protected function isAmbiguousMove(object $move): bool
    {
        $ambiguous = [];
        foreach ($this->pickPiece($move) as $piece) {
            if ($piece->isMovable() && !$this->leavesInCheck($piece)) {
                foreach ($piece->sqs() as $sq) {
                    if ($move->sq->next === $sq) {
                        $ambiguous[] = $sq;
                    }
                }
            }
        }

        return count($ambiguous) > 1;
    }

    /**
     * Checks out if a capture is ambiguous.
     *
     * @param object $move
     * @return bool true if the capture is ambiguous; otherwise false
     */
    protected function isAmbiguousCapture(object $move): bool
    {
        // because of the en passant rule
        if ($move->id !== Piece::P) {
            if ($move->isCapture && !$this->getPieceBySq($move->sq->next)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks out if a chess move is legal.
     *
     * @param object $move
     * @return bool true if the move is legal; otherwise false
     */
    protected function isLegalMove(object $move): bool
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
                if ($piece->getMove()->type === $this->move->case(MOVE::CASTLE_SHORT)) {
                    return $this->castle($piece, RType::CASTLE_SHORT);
                } elseif ($piece->getMove()->type === $this->move->case(MOVE::CASTLE_LONG)) {
                    return $this->castle($piece, RType::CASTLE_LONG);
                } else {
                    return $this->move($piece);
                }
            }
         }

        return false;
    }

    /**
     * Makes a move in PGN format.
     *
     * @param string $color
     * @param string $pgn
     * @return bool true if the move can be made; otherwise false
     */
    public function play(string $color, string $pgn): bool
    {
        $obj = $this->move->toObj($color, $pgn, $this->castlingRule);

        return $this->isValidMove($obj) && $this->isLegalMove($obj);
    }

    /**
     * Makes a move in long algebraic notation.
     *
     * @param string $color
     * @param string $lan
     * @return bool true if the move can be made; otherwise false
     */
    public function playLan(string $color, string $lan): bool
    {
        $sqs = $this->move->explodeSqs($lan);
        if (isset($sqs[0]) && isset($sqs[1])) {
            if ($color === $this->getTurn() && $piece = $this->getPieceBySq($sqs[0])) {
                switch ($piece->getId()) {
                    case Piece::K:
                        if (
                            $this->castlingRule[$color][Piece::K][Castle::SHORT]['sq']['next'] === $sqs[1] &&
                            $piece->sqCastleShort() &&
                            $this->play($color, Castle::SHORT)
                        ) {
                            return true;
                        } elseif (
                            $this->castlingRule[$color][Piece::K][Castle::LONG]['sq']['next'] === $sqs[1] &&
                            $piece->sqCastleLong() &&
                            $this->play($color, Castle::LONG)
                        ) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, Piece::K.'x'.$sqs[1])) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, Piece::K.$sqs[1])) {
                            return $this->afterPlayLan();
                        }
                        break;
                    case Piece::P:
                        try {
                            if ($this->play($color, $sqs[1])) {
                                return $this->afterPlayLan();
                            }
                        } catch (\Exception $e) {
                        }
                        try {
                            if ($this->play($color, $piece->getSqFile()."x$sqs[1]")) {
                                return $this->afterPlayLan();
                            }
                        } catch (\Exception $e) {
                        }
                        break;
                    default:
                        if ($this->play($color, "{$piece->getId()}x$sqs[1]")) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, $piece->getId().$sqs[1])) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, "{$piece->getId()}{$piece->getSqFile()}x$sqs[1]")) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, $piece->getId().$piece->getSqFile().$sqs[1])) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, "{$piece->getId()}{$piece->getSqRank()}x$sqs[1]")) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, $piece->getId().$piece->getSqRank().$sqs[1])) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, "{$piece->getId()}{$piece->getSq()}x$sqs[1]")) {
                            return $this->afterPlayLan();
                        } elseif ($this->play($color, $piece->getId().$piece->getSq().$sqs[1])) {
                            return $this->afterPlayLan();
                        }
                        break;
                }
            }
        }

        return false;
    }

    /**
     * Updates the history after a move in long algebrac notation is made.
     *
     * @return bool
     */
    private function afterPlayLan(): bool
    {
        $end = $this->getHistory()[count($this->getHistory()) - 1];
        if ($this->isMate()) {
            $end->move->pgn .= '#';
        } elseif ($this->isCheck()) {
            $end->move->pgn .= '+';
        }
        $this->getHistory()[count($this->getHistory()) - 1] = $end;

        return true;
    }

    /**
     * Returns a new piece.
     *
     * @return AbstractPiece
     */
    private function newPiece($color, $sq, $piece): AbstractPiece
    {
        $className = "\\Chess\\Piece\\{$piece->getId()}";
        if ($piece->getId() === Piece::R) {
            return new $className($color, $sq, $this->size, $piece->getType());
        }

        return new $className($color, $sq, $this->size);
    }

    /**
     * Castles the king.
     *
     * @param \Chess\Piece\K $king
     * @param string $rookType
     * @return bool true if the castle move can be made; otherwise false
     */
    private function castle(K $king, string $rookType): bool
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
     * Undoes a castle move.
     *
     * @param string $sq
     * @param object $move
     * @return \Chess\Variant\Classical\Board
     */
    private function undoCastle(string $sq, object $move): Board
    {
        $king = $this->getPieceBySq($move->sq->next);
        $kingUndone = new K($move->color, $sq, $this->castlingRule);
        $this->detach($king);
        $this->attach($kingUndone);
        if ($this->move->case(MOVE::CASTLE_SHORT) === $move->type) {
            $rook = $this->getPieceBySq(
                $this->castlingRule[$move->color][Piece::R][Castle::SHORT]['sq']['next']
            );
            $rookUndone = new R(
                $move->color,
                $this->castlingRule[$move->color][Piece::R][Castle::SHORT]['sq']['current'],
                $this->size,
                $rook->getType()
            );
            $this->detach($rook);
            $this->attach($rookUndone);
        } elseif ($this->move->case(MOVE::CASTLE_LONG) === $move->type) {
            $rook = $this->getPieceBySq(
                $this->castlingRule[$move->color][Piece::R][Castle::LONG]['sq']['next']
            );
            $rookUndone = new R(
                $move->color,
                $this->castlingRule[$move->color][Piece::R][Castle::LONG]['sq']['current'],
                $this->size,
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
     * @return \Chess\Variant\Classical\Board
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
                    $this->castlingRule[$oppColor][Piece::R][Castle::SHORT]['sq']['current']
                ) {
                    $this->castlingAbility = CastlingAbility::remove(
                        $this->castlingAbility,
                        $oppColor,
                        [Piece::K]
                    );
                } elseif (
                    $pieceMoved->getMove()->sq->next ===
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
        if ($toDetach = $this->getPieceBySq($piece->getSq())) {
            $this->detach($toDetach);
        }
        $this->attach($this->newPiece(
            $piece->getColor(),
            $piece->getMove()->sq->next,
            $piece
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
     * @return \Chess\Variant\Classical\Board
     */
    private function undoMove(string $sq, object $move): Board
    {
        $piece = $this->getPieceBySq($move->sq->next);
        $this->detach($piece);
        if (
            $move->type === $this->move->case(MOVE::PAWN_PROMOTES) ||
            $move->type === $this->move->case(MOVE::PAWN_CAPTURES_AND_PROMOTES)
        ) {
            $pieceUndone = new P($move->color, $sq, $this->size);
            $this->attach($pieceUndone);
        } else {
            $pieceUndone = $this->newPiece($move->color, $sq, $piece);
            $this->attach($pieceUndone);
        }
        if ($move->isCapture && $capture = end($this->captures[$move->color])) {
            $className = "\\Chess\\Piece\\{$capture->captured->id}";
            $this->attach(new $className(
                Color::opp($move->color),
                $capture->captured->sq,
                $this->size,
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
     * @return \Chess\Variant\Classical\Board
     */
    public function undo(): Board
    {
        if ($last = end($this->history)) {
            if (
                $last->move->type === $this->move->case(MOVE::CASTLE_SHORT) ||
                $last->move->type === $this->move->case(MOVE::CASTLE_LONG)
            ) {
                $this->undoCastle($last->sq, $last->move);
                $nextToLast = end($this->history);
                $this->castlingAbility = $nextToLast->castlingAbility;
            } elseif (
                $last->move->type === $this->move->case(MOVE::KING) ||
                $last->move->type === $this->move->case(MOVE::KING_CAPTURES)
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

    private function leavesInCheck(AbstractPiece $piece): bool
    {
        $clone = unserialize(serialize($this));
        if (
            $piece->getMove()->type === $clone->move->case(MOVE::CASTLE_SHORT) &&
            $clone->castle($piece, RType::CASTLE_SHORT)
        ) {
            $king = $clone->getPiece($piece->getColor(), Piece::K);
        } elseif (
            $piece->getMove()->type === $clone->move->case(MOVE::CASTLE_LONG) &&
            $clone->castle($piece, RType::CASTLE_LONG)
        ) {
            $king = $clone->getPiece($piece->getColor(), Piece::K);
        } else {
            $clone->move($piece);
            $king = $clone->getPiece($piece->getColor(), Piece::K);
        }

        return in_array($king->getSq(), $clone->pressureEval->{$king->oppColor()});
    }

    /**
     * Checks out whether the current player is trapped.
     *
     * @return bool
     */
    private function isTrapped(): bool
    {
        $escape = 0;
        foreach ($this->getPieces($this->turn) as $piece) {
            foreach ($piece->sqs() as $sq) {
                if ($piece->getId() === Piece::K) {
                    if ($sq === $piece->sqCastleShort()) {
                        $move = $this->move->toObj($this->turn, Castle::SHORT, $this->castlingRule);
                    } elseif ($sq === $piece->sqCastleLong()) {
                        $move = $this->move->toObj($this->turn, CASTLE::LONG, $this->castlingRule);
                    } elseif (in_array($sq, $this->sqEval->used->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, Piece::K."x$sq", $this->castlingRule);
                    } elseif (!in_array($sq, $this->spaceEval->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, Piece::K.$sq, $this->castlingRule);
                    }
                } elseif ($piece->getId() === Piece::P) {
                    if (in_array($sq, $this->sqEval->used->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, $piece->getSqFile()."x$sq", $this->castlingRule);
                    } else {
                        $move = $this->move->toObj($this->turn, $sq, $this->castlingRule);
                    }
                } else {
                    if (in_array($sq, $this->sqEval->used->{$piece->oppColor()})) {
                        $move = $this->move->toObj($this->turn, $piece->getId()."x$sq", $this->castlingRule);
                    } else {
                        $move = $this->move->toObj($this->turn, $piece->getId().$sq, $this->castlingRule);
                    }
                }
                $escape += (int) !$this->leavesInCheck($piece->setMove($move));
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
     * Returns the legal squares of a piece.
     *
     * @param string $sq
     * @return object|null
     */
     public function legalSqs(string $sq): ?object
     {
         if ($piece = $this->getPieceBySq($sq)) {
             $sqs = [];
             $color = $piece->getColor();
             foreach ($piece->sqs() as $sq) {
                 $clone = unserialize(serialize($this));
                 try {
                    switch ($piece->getId()) {
                        case Piece::K:
                            if ($clone->play($color, Piece::K.'x'.$sq)) {
                                $sqs[] = $sq;
                            } elseif ($clone->play($color, Piece::K.$sq)) {
                                $sqs[] = $sq;
                            }
                            break;
                        case Piece::P:
                            if ($clone->play($color, $piece->getSqFile()."x$sq")) {
                                $sqs[] = $sq;
                            } elseif ($clone->play($color, $sq)) {
                                $sqs[] = $sq;
                            }
                            break;
                        default:
                            if ($clone->play($color, "{$piece->getId()}x$sq")) {
                                $sqs[] = $sq;
                            } elseif ($clone->play($color, $piece->getId().$sq)) {
                                $sqs[] = $sq;
                            } elseif ($clone->play($color, "{$piece->getId()}{$piece->getSqFile()}x$sq")) {
                                $sqs[] = $sq; // disambiguation by file
                            } elseif ($clone->play($color, $piece->getId().$piece->getSqFile().$sq)) {
                                $sqs[] = $sq; // disambiguation by file
                            } elseif ($clone->play($color, "{$piece->getId()}{$piece->getSqRank()}x$sq")) {
                                $sqs[] = $sq; // disambiguation by rank
                            } elseif ($clone->play($color, $piece->getId().$piece->getSqRank().$sq)) {
                                $sqs[] = $sq; // disambiguation by rank
                            } elseif ($clone->play($color, "{$piece->getId()}{$piece->getSq()}x$sq")) {
                                $sqs[] = $sq; // disambiguation by square
                            } elseif ($clone->play($color, $piece->getId().$piece->getSq().$sq)) {
                                $sqs[] = $sq; // disambiguation by square
                            }
                            break;
                    }
                } catch (\Exception $e) {
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
        $array = [];
        for ($i = $this->size['ranks'] - 1; $i >= 0; $i--) {
            $array[$i] = array_fill(0, $this->size['files'], ' . ');
        }

        foreach ($this->getPieces() as $piece) {
            $sq = $piece->getSq();
            list($file, $rank) = AsciiArray::fromAlgebraicToIndex($sq);
            if ($flip) {
                $diff = $this->size['files'] - $this->size['ranks'];
                $file = $this->size['files'] - 1 - $file - $diff;
                $rank = $this->size['ranks'] - 1 - $rank + $diff;
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
