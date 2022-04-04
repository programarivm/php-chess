<?php

namespace Chess\Piece;

use Chess\Piece\Piece;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;

/**
 * AbstractPiece
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractPiece implements Piece
{
    use PieceObserverBoardTrait;

    /**
     * The piece's color in PGN format.
     *
     * @var string
     */
    protected $color;

    /**
     * The piece's position on the board.
     *
     * @var \stdClass
     */
    protected $position;

    /**
     * The piece's scope.
     *
     * @var array
     */
    protected $scope = [];

    /**
     * The piece's identity in PGN format.
     *
     * @var string
     */
    protected $identity;

    /**
     * The piece's next move to be performed on the board.
     *
     * @var \stdClass
     */
    protected $move;

    /**
     * The legal moves that the piece can carry out.
     *
     * @var array
     */
    protected $legalMoves;

    /**
     * The chessboard.
     *
     * @var \Chess\Board
     */
    protected $board;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     * @param string $identity
     */
    public function __construct(string $color, string $square, string $identity)
    {
        $this->color = Validate::color($color);
        $this->position = Validate::square($square);
        $this->identity = $identity;
    }

    /**
     * Gets the squares where a piece can be placed on.
     *
     * @return array The piece's legal squares.
     */
    abstract public function getSquares(): array;

    /**
     * Calculates the piece's scope.
     */
    abstract protected function scope(): void;

    /**
     * Gets the piece's color.
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Gets the piece's opposite color.
     *
     * @return string
     */
    public function getOppColor(): string
    {
        if ($this->color == Symbol::WHITE) {
            return Symbol::BLACK;
        } else {
            return Symbol::WHITE;
        }
    }

    /**
     * Gets the piece's position on the board.
     *
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Gets the piece's scope.
     *
     * @return \stdClass
     */
    public function getScope(): \stdClass
    {
        return $this->scope;
    }

    /**
     * Gets the piece's identity.
     *
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * Gets the piece's move.
     *
     * @return \stdClass
     */
    public function getMove()
    {
        return $this->move;
    }

    /**
     * Sets the piece's next move.
     *
     * @param \stdClass $move
     */
    public function setMove(\stdClass $move): Piece
    {
        $this->move = $move;

        return $this;
    }

    /**
     * Checks whether or not the piece can be moved.
     *
     * @return boolean true if the piece can be moved; otherwise false
     */
    public function isMovable(): bool
    {
        if (isset($this->move)) {
            return in_array($this->move->position->next, $this->getSquares());
        } else {
            return false;
        }
    }
}
