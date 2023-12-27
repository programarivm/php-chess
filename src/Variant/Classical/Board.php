<?php

namespace Chess\Variant\Classical;

use Chess\FenToBoard;
use Chess\Eval\SpaceEval;
use Chess\Eval\SqCount;
use Chess\Piece\AbstractPiece;
use Chess\Piece\AsciiArray;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Classical\FEN\StrToBoard;
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
 * @author Jordi Bassagaña
 * @license GPL
 */
class Board extends \SplObjectStorage
{
    use BoardObserverPieceTrait;

    const VARIANT = 'classical';

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
     * Observers.
     *
     * @var array
     */
    private array $observers;

    /**
     * Space evaluation.
     *
     * @var object
     */
    private object $spaceEval;

    /**
     * Count squares.
     *
     * @var object
     */
    private object $sqCount;

    /**
     * Constructor.
     *
     * @param array $pieces
     * @param string $castlingAbility
     */
    public function __construct(
        array $pieces = null,
        string $castlingAbility = '-'
    ) {
        $this->size = Square::SIZE;
        $this->sqs = Square::all();
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

        $this->startFen = $this->toFen();
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
    public function getSqCount(): object
    {
        return $this->sqCount;
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
     * Returns the en passant square.
     *
     * @return string
     */
    public function enPassant(): string
    {
        if ($this->history) {
            $last = array_slice($this->history, -1)[0];
            if ($last->move->id === Piece::P) {
                $prevFile = intval(substr($last->sq, 1));
                $nextFile = intval(substr($last->move->sq->next, 1));
                if ($last->move->color === Color::W) {
                    if ($nextFile - $prevFile === 2) {
                        $rank = $prevFile + 1;
                        return $last->move->sq->current.$rank;
                    }
                } elseif ($prevFile - $nextFile === 2) {
                    $rank = $prevFile - 1;
                    return $last->move->sq->current.$rank;
                }
            }
        }

        return '-';
    }

    /**
     * Returns the movetext.
     *
     * @return string
     */
    public function getMovetext(): string
    {
        $movetext = '';
        if (isset($this->history[0]->move)) {
            if ($this->history[0]->move->color === Color::W) {
                $movetext = "1.{$this->history[0]->move->pgn}";
            } else {
                $movetext = '1' . Move::ELLIPSIS . "{$this->history[0]->move->pgn} ";
            }
        }
        for ($i = 1; $i < count($this->history); $i++) {
            if ($this->history[0]->move->color === Color::W) {
                if ($i % 2 === 0) {
                    $movetext .= ($i / 2 + 1) . ".{$this->history[$i]->move->pgn}";
                } else {
                    $movetext .= " {$this->history[$i]->move->pgn} ";
                }
            } else {
                if ($i % 2 === 0) {
                    $movetext .= " {$this->history[$i]->move->pgn} ";
                } else {
                    $movetext .= (ceil($i / 2) + 1) . ".{$this->history[$i]->move->pgn}";
                }
            }
        }

        return trim($movetext);
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
     */
    private function pickPiece(object $move): array
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
     * Captures a piece.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return \Chess\Variant\Classical\Board
     */
    private function capture(AbstractPiece $piece): Board
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
     * Promotes a pawn.
     *
     * @param \Chess\Piece\P $pawn
     * @return \Chess\Variant\Classical\Board
     */
    private function promote(P $pawn): Board
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
     * Checks out if a move is syntactically valid.
     *
     * @param object $move
     * @return bool true if the move is valid; otherwise false
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
     * Checks out if a move is ambiguous.
     *
     * @param object $move
     * @return bool true if the move is not ambiguous; otherwise false
     */
    protected function isAmbiguousMove(object $move): bool
    {
        $ambiguous = [];
        foreach ($this->pickPiece($move) as $piece) {
            if (!$this->isPinned($piece) && in_array($move->sq->next, $piece->sqs())) {
                $ambiguous[] = $move->sq->next;
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
     * Checks out if a chess move is legal.
     *
     * @param object $move
     * @return bool true if the move is legal; otherwise false
     */
    protected function isLegalMove(object $move): bool
    {
        foreach ($pieces = $this->pickPiece($move) as $piece) {
            if ($piece->isMovable() && !$this->isPinned($piece)) {
                if ($piece->getMove()->type === $this->move->case(Move::CASTLE_SHORT)) {
                    return $this->castle($piece, RType::CASTLE_SHORT);
                } elseif ($piece->getMove()->type === $this->move->case(Move::CASTLE_LONG)) {
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
        $move = $this->move->toObj($color, $pgn, $this->castlingRule);
        if ($this->isValidMove($move)) {
            return $this->isLegalMove($move);
        }

        return false;
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
               if ($piece->getId() === Piece::K) {
                   if (
                       $this->castlingRule[$color][Piece::K][Castle::SHORT]['sq']['next'] === $sqs[1] &&
                       $piece->sqCastleShort() &&
                       $this->play($color, Castle::SHORT)
                   ) {
                       return $this->addSymbol();
                   } elseif (
                       $this->castlingRule[$color][Piece::K][Castle::LONG]['sq']['next'] === $sqs[1] &&
                       $piece->sqCastleLong() &&
                       $this->play($color, Castle::LONG)
                   ) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, Piece::K.'x'.$sqs[1])) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, Piece::K.$sqs[1])) {
                       return $this->addSymbol();
                   }
               } elseif ($piece->getId() === Piece::P) {
                   strlen($lan) === 5
                       ? $promotion = '='.mb_strtoupper(substr($lan, -1))
                       : $promotion = '';
                   if ($this->play($color, $piece->getSqFile()."x$sqs[1]".$promotion)) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, $sqs[1].$promotion)) {
                       return $this->addSymbol();
                   }
               } else {
                   if ($this->play($color, "{$piece->getId()}x$sqs[1]")) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, "{$piece->getId()}{$piece->getSqFile()}x$sqs[1]")) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, "{$piece->getId()}{$piece->getSqRank()}x$sqs[1]")) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, $piece->getId().$sqs[1])) {
                       return $this->addSymbol();
                   }  elseif ($this->play($color, $piece->getId().$piece->getSqFile().$sqs[1])) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, $piece->getId().$piece->getSqRank().$sqs[1])) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, "{$piece->getId()}{$piece->getSq()}x$sqs[1]")) {
                       return $this->addSymbol();
                   } elseif ($this->play($color, $piece->getId().$piece->getSq().$sqs[1])) {
                       return $this->addSymbol();
                   }
               }
           }
       }

       return false;
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
     * Updates the castle property.
     *
     * @param \Chess\Piece\AbstractPiece $piece The moved piece
     * @return \Chess\Variant\Classical\Board
     */
    private function updateCastle(AbstractPiece $piece): Board
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
     * Undoes the last move.
     *
     * @return \Chess\Variant\Classical\Board
     */
    public function undo(): Board
    {
        $board = FenToBoard::create($this->getStartFen(), $this);
        foreach ($this->popHistory()->getHistory() as $key => $val) {
            $board->play($val->move->color, $val->move->pgn);
        }

        return $board;
    }

    /**
     * Refreshes the state of the board.
     */
    public function refresh(): void
    {
        $this->turn = Color::opp($this->turn);

        $this->sqCount = (new SqCount($this))->count();

        $this->detachPieces()
            ->attachPieces()
            ->notifyPieces();

        $this->spaceEval = (object) (new SpaceEval($this))->getResult();

        $this->notifyPieces();

        if ($this->history) {
            $this->history[count($this->history) - 1]->fen = $this->toFen();
        }
    }

    /**
     * Checks out if a piece is pinned.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @return bool true if the piece is pinned; otherwise false
     */
    private function isPinned(AbstractPiece $piece): bool
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
     * Checks out whether the current player is in check.
     *
     * @return bool
     */
    public function isCheck(): bool
    {
        $king = $this->getPiece($this->turn, Piece::K);

        return !empty($king->attackingPieces());
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
     * Adds a PGN symbol to the last history element.
     *
     * @return bool
     */
    private function addSymbol(): bool
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
     * Checks out whether the current player is stalemated.
     *
     * @return bool
     */
    public function isStalemate(): bool
    {
        return $this->isTrapped() && !$this->isCheck();
    }

    /**
     * Checks out whether the same position occurs five times.
     *
     * @return bool
     */
    public function isFivefoldRepetition(): bool
    {
        $count = array_count_values(array_column($this->history, 'fen'));
        foreach ($count as $key => $val) {
            if ($val >= 5) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the legal FEN positions of a piece.
     *
     * @param string $sq
     * @return object|null
     */
     public function legal(string $sq): ?object
     {
         if ($piece = $this->getPieceBySq($sq)) {
            $fen = [];
            foreach ($piece->sqs() as $sq) {
                if ($res = $piece->fen($piece->getColor(), $sq)) {
                    $fen[$sq] = $res;
                }
            }

            return (object) [
                'color' => $piece->getColor(),
                'id' => $piece->getId(),
                'fen' => (object) $fen,
            ];
         }

         return null;
     }

    /**
     * Returns an ASCII array representing this chessboard object.
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
     * Returns a FEN representing this chessboard object.
     *
     * @return string
     */
    public function toFen(): string
    {
        $string = '';
        $array = $this->toAsciiArray();
        for ($i = $this->getSize()['ranks'] - 1; $i >= 0; $i--) {
            $string .= str_replace(' ', '', implode('', $array[$i]));
            if ($i != 0) {
                $string .= '/';
            }
        }

        $filtered = '';
        $strSplit = str_split($string);
        $n = 1;
        for ($i = 0; $i < count($strSplit); $i++) {
            if ($strSplit[$i] === '.') {
                if (isset($strSplit[$i+1]) && $strSplit[$i+1] === '.') {
                    $n++;
                } else {
                    $filtered .= $n;
                    $n = 1;
                }
            } else {
                $filtered .= $strSplit[$i];
                $n = 1;
            }
        }

        return "{$filtered} {$this->getTurn()} {$this->getCastlingAbility()} {$this->enPassant()}";
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
