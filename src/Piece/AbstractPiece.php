<?php

namespace Chess\Piece;

use Chess\Piece\Piece;
use Chess\PGN\Convert;
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
     * The piece's travel.
     *
     * @var mixed object|array
     */
    protected $travel;

    /**
     * The piece's id in PGN format.
     *
     * @var string
     */
    protected $id;

    /**
     * The piece's next move to be performed on the board.
     *
     * @var \stdClass
     */
    protected $move;

    /**
     * The squares where the piece can be placed on.
     *
     * @var array
     */
    protected $sqs;

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
     * @param string $sq
     * @param string $id
     */
    public function __construct(string $color, string $sq, string $id)
    {
        $this->color = Validate::color($color);
        $this->sq = Validate::sq($sq);
        $this->id = $id;
    }

    /**
     * Gets the squares where the piece can be placed on.
     *
     * @return array The piece's legal squares.
     */
    abstract public function getSqs(): array;

    /**
     * Calculates the squares the piece could travel to.
     */
    abstract protected function setTravel(): void;

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
        return Convert::toOpposite($this->color);
    }

    /**
     * Gets the piece's position on the board.
     *
     * @return string
     */
    public function getSquare(): string
    {
        return $this->sq;
    }

    /**
     * Gets the piece's travel.
     *
     * @return \stdClass
     */
    public function getTravel()
    {
        return $this->travel;
    }

    /**
     * Gets the piece's id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
            return in_array($this->move->sq->next, $this->getSqs());
        }

        return false;
    }
}
