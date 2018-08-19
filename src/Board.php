<?php

namespace PGNChess;

use PGNChess\Exception\BoardException;
use PGNChess\Square\Castling;
use PGNChess\Square\Stats;
use PGNChess\PGN\Convert;
use PGNChess\PGN\Move;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\King;
use PGNChess\Piece\Knight;
use PGNChess\Piece\Pawn;
use PGNChess\Piece\Piece;
use PGNChess\Piece\Queen;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Type\RookType;

/**
 * Chess board.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
final class Board extends \SplObjectStorage
{
    /**
     * Current player's turn.
     *
     * @var string
     */
    private $turn;

    /**
     * Free/used squares.
     *
     * @var \stdClass
     */
    private $squares;

    /**
     * Squares controlled by both players.
     *
     * @var \stdClass
     */
    private $control;

    /**
     * Castling status.
     *
     * @var \stdClass
     */
    private $castling;

    /**
     * Both players' captures.
     *
     * @var \stdClass
     */
    private $captures;

    /**
     * History.
     *
     * @var array
     */
    private $history;

    /**
     * Constructor.
     *
     * @param array     $pieces
     * @param \stdClass $castling
     */
    public function __construct(array $pieces = null, \stdClass $castling = null)
    {
        if (empty($pieces)) {

            $this->attach(new Rook(Symbol::WHITE, 'a1', RookType::CASTLING_LONG));
            $this->attach(new Knight(Symbol::WHITE, 'b1'));
            $this->attach(new Bishop(Symbol::WHITE, 'c1'));
            $this->attach(new Queen(Symbol::WHITE, 'd1'));
            $this->attach(new King(Symbol::WHITE, 'e1'));
            $this->attach(new Bishop(Symbol::WHITE, 'f1'));
            $this->attach(new Knight(Symbol::WHITE, 'g1'));
            $this->attach(new Rook(Symbol::WHITE, 'h1', RookType::CASTLING_SHORT));
            $this->attach(new Pawn(Symbol::WHITE, 'a2'));
            $this->attach(new Pawn(Symbol::WHITE, 'b2'));
            $this->attach(new Pawn(Symbol::WHITE, 'c2'));
            $this->attach(new Pawn(Symbol::WHITE, 'd2'));
            $this->attach(new Pawn(Symbol::WHITE, 'e2'));
            $this->attach(new Pawn(Symbol::WHITE, 'f2'));
            $this->attach(new Pawn(Symbol::WHITE, 'g2'));
            $this->attach(new Pawn(Symbol::WHITE, 'h2'));
            $this->attach(new Rook(Symbol::BLACK, 'a8', RookType::CASTLING_LONG));
            $this->attach(new Knight(Symbol::BLACK, 'b8'));
            $this->attach(new Bishop(Symbol::BLACK, 'c8'));
            $this->attach(new Queen(Symbol::BLACK, 'd8'));
            $this->attach(new King(Symbol::BLACK, 'e8'));
            $this->attach(new Bishop(Symbol::BLACK, 'f8'));
            $this->attach(new Knight(Symbol::BLACK, 'g8'));
            $this->attach(new Rook(Symbol::BLACK, 'h8', RookType::CASTLING_SHORT));
            $this->attach(new Pawn(Symbol::BLACK, 'a7'));
            $this->attach(new Pawn(Symbol::BLACK, 'b7'));
            $this->attach(new Pawn(Symbol::BLACK, 'c7'));
            $this->attach(new Pawn(Symbol::BLACK, 'd7'));
            $this->attach(new Pawn(Symbol::BLACK, 'e7'));
            $this->attach(new Pawn(Symbol::BLACK, 'f7'));
            $this->attach(new Pawn(Symbol::BLACK, 'g7'));
            $this->attach(new Pawn(Symbol::BLACK, 'h7'));

            $this->castling = (object) [
                Symbol::WHITE => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true,
            ],
                Symbol::BLACK => (object) [
                'castled' => false,
                Symbol::CASTLING_SHORT => true,
                Symbol::CASTLING_LONG => true,
            ], ];

        } else {
            foreach ($pieces as $piece) {
                $this->attach($piece);
            }

            $this->castling = $castling;

            Analyze::castling($this);
        }

        $this->captures = (object) [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];

        $this->history = [];

        $this->refresh();
    }

    /**
     * Gets the current turn.
     *
     * @return string
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Sets the current turn.
     *
     * @param string $color
     * @return \PGNChess\Board
     */
    public function setTurn($color)
    {
        $this->turn = PgnValidate::color($color);

        return $this;
    }

    /**
     * Gets the free/used squares.
     *
     * @return \stdClass
     */
    public function getSquares()
    {
        return $this->squares;
    }

    /**
     * Sets the free/used squares.
     *
     * @param \stdClass $squares
     * @return \PGNChess\Board
     */
    private function setSquares(\stdClass $squares)
    {
        $this->squares = $squares;

        return $this;
    }

    /**
     * Gets the squares controlled by both players.
     *
     * @return \stdClass
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Sets the squares controlled by both players.
     *
     * @param \stdClass $control
     * @return \PGNChess\Board
     */
    private function setControl(\stdClass $control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Gets the castling status.
     *
     * @return \stdClass
     */
    public function getCastling()
    {
        return $this->castling;
    }

    /**
     * Gets the captures of both players.
     *
     * @return \stdClass
     */
    public function getCaptures()
    {
        return $this->captures;
    }

    /**
     * Adds a new capture.
     *
     * @param string    $color
     * @param \stdClass $capture
     * @return \PGNChess\Board
     */
    private function pushCapture($color, \stdClass $capture)
    {
        $this->captures->{$color}[] = $capture;

        return $this;
    }

    /**
     * Removes an element from the array of captures.
     *
     * @param string $color
     */
    private function popCapture($color)
    {
        array_pop($this->captures->{$color});

        return $this;
    }

    /**
     * Gets the history.
     *
     * @return array
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Adds a new entry to the history.
     *
     * @param \stdClass $piece The piece's previous position along with a move object
     * @return \PGNChess\Board
     */
    private function pushHistory(Piece $piece)
    {
        $entry = (object) [
            'position' => $piece->getPosition(),
            'move' => $piece->getMove(),
        ];

        $piece->getIdentity() === Symbol::ROOK ? $entry->type = $piece->getType() : null;

        $this->history[] = $entry;

        return $this;
    }

    /**
     * Removes an element from the history.
     *
     * @param string $color
     */
    private function popHistory()
    {
        array_pop($this->history);

        return $this;
    }

    /**
     * Gets the first piece on the board meeting the search criteria.
     *
     * @param string $color
     * @param string $identity
     * @return mixed \PGNChess\Piece\Piece|null
     */
    public function getPiece($color, $identity)
    {
        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->getColor() === $color && $piece->getIdentity() === $identity) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

    /**
     * Gets all pieces by color.
     *
     * @param string $color
     * @return array
     */
    public function getPiecesByColor($color)
    {
        $pieces = [];
        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            $piece->getColor() === $color ? $pieces[] = $piece : false;
            $this->next();
        }

        return $pieces;
    }

    /**
     * Gets a piece by its position on the board.
     *
     * @param string $square
     * @return mixed \PGNChess\Piece\Piece|null
     */
    public function getPieceByPosition($square)
    {
        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            if ($piece->getPosition() === $square) {
                return $piece;
            }
            $this->next();
        }

        return null;
    }

    /**
     * Picks a piece to be moved.
     *
     * @param \stdClass $move
     * @return array The piece(s) matching the PGN move; otherwise null
     * @throws \PGNChess\Exception\BoardException
     */
    private function pickPiece(\stdClass $move)
    {
        $found = [];

        $pieces = $this->getPiecesByColor($move->color);

        foreach ($pieces as $piece) {
            if ($piece->getIdentity() === $move->identity) {
                switch ($piece->getIdentity()) {

                    case Symbol::KING:
                        return [$piece->setMove($move)];
                        break;

                    default:
                        if (preg_match("/{$move->position->current}/", $piece->getPosition())) {
                            $found[] = $piece->setMove($move);
                        }
                        break;
                }
            }
        }

        if (empty($found)) {
            throw new BoardException(
                "This piece does not exist: {$move->color} {$move->identity} on {$move->position->current}."
            );
        } else {
            return $found;
        }
    }

    /**
     * Captures a piece.
     *
     * @param \PGNChess\Piece\Piece $piece
     * @return \PGNChess\Board
     */
    private function capture(Piece $piece)
    {
        $piece->getLegalMoves(); // this creates the enPassantSquare property in the pawn's position object

        if ($piece->getIdentity() === Symbol::PAWN && !empty($piece->getEnPassantSquare()) &&
            empty($this->getPieceByPosition($piece->getMove()->position->next))
           ) {
            $captured = $this->getPieceByPosition($piece->getEnPassantSquare());
            $capturedData = (object) [
                'identity' => $captured->getIdentity(),
                'position' => $piece->getEnPassantSquare(),
            ];
        } else {
            $captured = $this->getPieceByPosition($piece->getMove()->position->next);
            $capturedData = (object) [
                'identity' => $captured->getIdentity(),
                'position' => $captured->getPosition(),
            ];
        }

        $captured->getIdentity() === Symbol::ROOK ? $capturedData->type = $captured->getType() : null;

        $this->detach($captured);

        $capturingData = (object) [
            'identity' => $piece->getIdentity(),
            'position' => $piece->getPosition(),
        ];

        $piece->getIdentity() === Symbol::ROOK ? $capturingData->type = $piece->getType() : null;

        $capture = (object) [
            'capturing' => $capturingData,
            'captured' => $capturedData,
        ];

        $this->pushCapture($piece->getColor(), $capture);

        return $this;
    }

    /**
     * Promotes a pawn.
     *
     * @param \PGNChess\Piece\Pawn $pawn
     * @return \PGNChess\Board
     */
    private function promote(Pawn $pawn)
    {
        $this->detach($this->getPieceByPosition($pawn->getMove()->position->next));

        switch ($pawn->getMove()->newIdentity) {
            case Symbol::KNIGHT:
                $this->attach(new Knight($pawn->getColor(), $pawn->getMove()->position->next));
                break;
            case Symbol::BISHOP:
                $this->attach(new Bishop($pawn->getColor(), $pawn->getMove()->position->next));
                break;
            case Symbol::ROOK:
                $this->attach(new Rook($pawn->getColor(), $pawn->getMove()->position->next, RookType::PROMOTED));
                break;
            default:
                $this->attach(new Queen($pawn->getColor(), $pawn->getMove()->position->next));
                break;
        }

        return $this;
    }

    /**
     * Runs a chess move on the board.
     *
     * @param \stdClass $move
     * @return bool true if the move is successfully run; otherwise false
     */
    public function play(\stdClass $move)
    {
        if ($move->color !== $this->turn) {
            return false;
        }

        $isLegalMove = false;

        $pieces = $this->pickPiece($move);

        if (count($pieces) > 1) {
            foreach ($pieces as $piece) {
                if ($piece->isMovable() && !$this->leavesInCheck($piece)) {
                    return $this->move($piece);
                }
            }
        } elseif (current($pieces)->isMovable() && !$this->leavesInCheck(current($pieces))) {
            $piece = current($pieces);

            switch ($piece->getMove()->type) {
                case Move::KING_CASTLING_SHORT:
                    $this->canCastleShort($this->turn) ? $isLegalMove = $this->castle($piece) : $isLegalMove = false;
                    break;
                case Move::KING_CASTLING_LONG:
                    $this->canCastleLong($this->turn) ? $isLegalMove = $this->castle($piece) : $isLegalMove = false;
                    break;
                default:
                    $isLegalMove = $this->move($piece);
                    break;
            }
        }

        return $isLegalMove;
    }

    /**
     * Calculates whether the king can castle short.
     *
     * @param string $color
     * @return bool
     */
    private function canCastleShort($color)
    {
        return $this->castling->{$color}->{Symbol::CASTLING_SHORT} &&
            !(in_array(
                Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->f,
                $this->control->space->{Symbol::oppositeColor($color)})
             ) &&
            !(in_array(
                Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_SHORT}->squares->g,
                $this->control->space->{Symbol::oppositeColor($color)})
             );
    }

    /**
     * Calculates whether the king can castle long.
     *
     * @param string $color
     * @return bool
     */
    private function canCastleLong($color)
    {
        return $this->castling->{$color}->{Symbol::CASTLING_LONG} &&
            !(in_array(
                Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->b,
                $this->control->space->{Symbol::oppositeColor($color)})
             ) &&
            !(in_array(
                Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->c,
                $this->control->space->{Symbol::oppositeColor($color)})
             ) &&
            !(in_array(
                Castling::info($color)->{Symbol::KING}->{Symbol::CASTLING_LONG}->squares->d,
                $this->control->space->{Symbol::oppositeColor($color)})
             );
    }

    /**
     * Castles the king.
     *
     * @param \PGNChess\Piece\King $king
     * @return bool true if the castling is successfully run; otherwise false
     */
    private function castle(King $king)
    {
        $rook = $king->getCastlingRook(iterator_to_array($this, false));

        if (!empty($rook)) {
            $this->detach($this->getPieceByPosition($king->getPosition()));
            $this->attach(new King(
                $king->getColor(),
                Castling::info($king->getColor())->{Symbol::KING}->{$king->getMove()->pgn}->position->next)
                         );

            $this->detach($rook);
            $this->attach(new Rook(
                $rook->getColor(),
                Castling::info($king->getColor())->{Symbol::ROOK}->{$king->getMove()->pgn}->position->next,
                $rook->getIdentity() === Symbol::ROOK
            ));

            $this->trackCastling(true)->pushHistory($king)->refresh();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Undoes a castle move.
     *
     * @param \stdClass $previousCastling
     * @return \PGNChess\Board
     */
    private function undoCastle($previousCastling)
    {
        $previous = end($this->history);

        $king = $this->getPieceByPosition($previous->move->position->next);
        $kingUndone = new King($previous->move->color, $previous->position);

        $this->detach($king);
        $this->attach($kingUndone);

        switch ($previous->move->type) {

            case Move::KING_CASTLING_SHORT:

                $rook = $this->getPieceByPosition(
                    Castling::info($previous->move->color)->{Symbol::ROOK}->{Symbol::CASTLING_SHORT}->position->next
                );

                $rookUndone = new Rook(
                    $previous->move->color,
                    Castling::info($previous->move->color)
                    ->{Symbol::ROOK}->{Symbol::CASTLING_SHORT}->position->current,
                    $rook->getType()
                );

                $this->detach($rook);
                $this->attach($rookUndone);

                break;

            case Move::KING_CASTLING_LONG:

                $rook = $this->getPieceByPosition(
                    Castling::info($previous->move->color)->{Symbol::ROOK}->{Symbol::CASTLING_LONG}->position->next
                );

                $rookUndone = new Rook(
                    $previous->move->color,
                    Castling::info($previous->move->color)->{Symbol::ROOK}->{Symbol::CASTLING_LONG}->position->current,
                    $rook->getType()
                );

                $this->detach($rook);
                $this->attach($rookUndone);

                break;
        }

        $this->castling = $previousCastling;
        $this->popHistory()->refresh();

        return $this;
    }

    /**
     * Updates the king's ability to castle.
     *
     * Setting the $castling param to true means that the king castled successfully.
     *
     * On the other hand, setting the $pieceMoved param is for when a move is run on
     * the board -- the program needs to figure out whether the piece moved was the king
     * or a rook.
     *
     * @param bool $castling
     * @param \PGNChess\Piece\Piece $pieceMoved
     * @return \PGNChess\Board
     * @throws \PGNChess\Exception\BoardException
     */
    private function trackCastling($castling = false, Piece $pieceMoved = null)
    {
        if ($castling && isset($pieceMoved)) {
            throw new BoardException("Error while tracking {$this->turn} king's ability to castle");
        }

        // king castled successfully
        if ($castling) {
            $this->castling->{$this->turn}->castled = true;
            $this->castling->{$this->turn}->{Symbol::CASTLING_SHORT} = false;
            $this->castling->{$this->turn}->{Symbol::CASTLING_LONG} = false;
        }

        // king/rook was moved
        if (isset($pieceMoved)) {
            if ($pieceMoved->getIdentity() === Symbol::KING) {
                $this->castling->{$this->turn}->castled = false;
                $this->castling->{$this->turn}->{Symbol::CASTLING_SHORT} = false;
                $this->castling->{$this->turn}->{Symbol::CASTLING_LONG} = false;
            } elseif ($pieceMoved->getIdentity() === Symbol::ROOK) {
                if ($pieceMoved->getType() === RookType::CASTLING_SHORT) {
                    $this->castling->{$this->turn}->{Symbol::CASTLING_SHORT} = false;
                } elseif ($pieceMoved->getType() === RookType::CASTLING_LONG) {
                    $this->castling->{$this->turn}->{Symbol::CASTLING_LONG} = false;
                }
            }
        }

        return $this;
    }

    /**
     * Moves a piece.
     *
     * @param \PGNChess\Piece\Piece $piece
     * @return bool true if the move is successfully run; otherwise false
     */
    private function move(Piece $piece)
    {
        if ($piece->getMove()->isCapture) {
            $this->capture($piece);
        }

        $this->detach($this->getPieceByPosition($piece->getPosition()));
        $pieceClass = new \ReflectionClass(get_class($piece));
        $this->attach($pieceClass->newInstanceArgs([
            $piece->getColor(),
            $piece->getMove()->position->next,
            $piece->getIdentity() === Symbol::ROOK ? $piece->getType() : null, ]
                                                  ));

        if ($piece->getIdentity() === Symbol::PAWN) {
            if ($piece->isPromoted()) {
                $this->promote($piece);
            }
        }

        if (!$this->castling->{$piece->getColor()}->castled) {
            $this->trackCastling(false, $piece);
        }

        $this->pushHistory($piece)->refresh();

        return true;
    }

    /**
     * Undoes the last move.
     *
     * @param \stdClass $previousCastling
     * @return \PGNChess\Board
     */
    private function undoMove($previousCastling)
    {
        $previous = end($this->history);

        $piece = $this->getPieceByPosition($previous->move->position->next);

        $this->detach($piece);

        if ($previous->move->type === Move::PAWN_PROMOTES ||
            $previous->move->type === Move::PAWN_CAPTURES_AND_PROMOTES) {
            $pieceUndone = new Pawn($previous->move->color, $previous->position);
        } else {
            $pieceUndoneClass = new \ReflectionClass(get_class($piece));
            $pieceUndone = $pieceUndoneClass->newInstanceArgs([
                $previous->move->color,
                $previous->position,
                $piece->getIdentity() === Symbol::ROOK ? $piece->getType() : null, ]
                                                             );
        }

        $this->attach($pieceUndone);

        if ($previous->move->isCapture) {
            $capture = end($this->getCaptures()->{$previous->move->color});

            $capturedClass = new \ReflectionClass(Convert::toClassName($capture->captured->identity));

            $this->attach($capturedClass->newInstanceArgs([
                $previous->move->color === Symbol::WHITE ? Symbol::BLACK : Symbol::WHITE,
                $capture->captured->position,
                $capture->captured->identity === Symbol::ROOK ? $capture->captured->type : null,
            ]));

            $this->popCapture($previous->move->color);
        }

        isset($previousCastling) ? $this->castling = $previousCastling : null;

        $this->popHistory()->refresh();

        return $this;
    }

    /**
     * Refreshes the board's status.
     *
     * This method is run just after a piece is moved successfully.
     *
     * @return \PGNChess\Board
     */
    private function refresh()
    {
        $this->turn = Symbol::oppositeColor($this->turn);
        $this->squares = Stats::calc(iterator_to_array($this, false));

        AbstractPiece::setBoardStatus((object) [
            'squares' => $this->squares,
            'castling' => $this->castling,
            'lastHistoryEntry' => !empty($this->history) ? end($this->history) : null,
        ]);

        $this->control = $this->control();

        AbstractPiece::setBoardControl($this->control);

        return $this;
    }

    /**
     * Builds an object containing the squares being controlled by both players.
     *
     * @return \stdClass
     */
    private function control()
    {
        $control = (object) [
            'space' => (object) [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ],
            'attack' => (object) [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ], ];

        $this->rewind();

        while ($this->valid()) {
            $piece = $this->current();
            switch ($piece->getIdentity()) {

                case Symbol::KING:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                        $control->space->{$piece->getColor()},
                        array_values(
                        array_intersect(
                        array_values((array) $piece->getScope()),
                        $this->squares->free
                    ))));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                        $control->attack->{$piece->getColor()},
                        array_values(
                        array_intersect(
                        array_values((array) $piece->getScope()),
                        $this->squares->used->{$piece->getOppositeColor()}
                    ))));
                    break;

                case Symbol::PAWN:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                        $control->space->{$piece->getColor()},
                        array_intersect(
                        $piece->getCaptureSquares(),
                        $this->squares->free
                    )));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                        $control->attack->{$piece->getColor()},
                        array_intersect(
                        $piece->getCaptureSquares(),
                        $this->squares->used->{$piece->getOppositeColor()}
                    )));
                    break;

                default:
                    $control->space->{$piece->getColor()} = array_unique(
                        array_merge(
                        $control->space->{$piece->getColor()},
                        array_diff(
                        $piece->getLegalMoves(),
                        $this->squares->used->{$piece->getOppositeColor()}
                    )));
                    $control->attack->{$piece->getColor()} = array_unique(
                        array_merge(
                        $control->attack->{$piece->getColor()},
                        array_intersect(
                        $piece->getLegalMoves(),
                        $this->squares->used->{$piece->getOppositeColor()}
                    )));
                    break;
            }
            $this->next();
        }

        sort($control->space->{Symbol::WHITE});
        sort($control->space->{Symbol::BLACK});
        sort($control->attack->{Symbol::WHITE});
        sort($control->attack->{Symbol::BLACK});

        return $control;
    }

    /**
     * Calculates if a piece's move leaves the board in check.
     *
     * @param \PGNChess\Piece\Piece $piece
     * @return bool
     */
    private function leavesInCheck(Piece $piece)
    {
        $previousCastling = unserialize(serialize($this->castling));

        if ($piece->getMove()->type === Move::KING_CASTLING_SHORT ||
            $piece->getMove()->type === Move::KING_CASTLING_LONG) {
            $this->castle($piece);
            $king = $this->getPiece($piece->getColor(), Symbol::KING);
            $leavesInCheck = in_array($king->getPosition(), $this->getControl()->attack->{$king->getOppositeColor()});
            $this->undoCastle($previousCastling);
        } else {
            $this->move($piece);
            $king = $this->getPiece($piece->getColor(), Symbol::KING);
            $leavesInCheck = in_array($king->getPosition(), $this->getControl()->attack->{$king->getOppositeColor()});
            $this->undoMove($previousCastling);
        }

        return $leavesInCheck;
    }

    /**
     * Calculates whether the current player is in check.
     *
     * @return bool
     */
    public function isCheck()
    {
        $king = $this->getPiece($this->turn, Symbol::KING);

        return in_array(
            $king->getPosition(),
            $this->control->attack->{$king->getOppositeColor()}
        );
    }

    /**
     * Calculates whether the current player is in mate.
     *
     * @return bool
     */
    public function isMate()
    {
        $escape = 0;

        $pieces = $this->getPiecesByColor($this->turn);

        foreach ($pieces as $piece) {
            $legalMoves = $piece->getLegalMoves();

            foreach ($legalMoves as $square) {
                switch ($piece->getIdentity()) {

                    case Symbol::KING:
                        if (in_array($square, $this->getSquares()->used->{$piece->getOppositeColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(
                                Convert::toObject($this->getTurn(), Symbol::KING."x$square")
                            ));
                        } elseif (!in_array($square, $this->getControl()->space->{$piece->getOppositeColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(
                                Convert::toObject($this->getTurn(), Symbol::KING.$square)
                            ));
                        }
                        break;

                    case Symbol::PAWN:
                        if (in_array($square, $this->getSquares()->used->{$piece->getOppositeColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(
                                Convert::toObject($this->getTurn(), $piece->getFile()."x$square")
                            ));
                        } else {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(Convert::toObject($this->getTurn(), $square)
                                               ));
                        }
                        break;

                    default:
                        if (in_array($square, $this->getSquares()->used->{$piece->getOppositeColor()})) {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(
                                Convert::toObject($this->getTurn(), $piece->getIdentity()."x$square")
                            ));
                        } else {
                            $escape += (int) !$this->leavesInCheck(
                                $piece->setMove(
                                Convert::toObject($this->getTurn(), $piece->getIdentity().$square)
                            ));
                        }
                        break;
                }
            }
        }

        return $escape === 0;
    }
}
