<?php

namespace Chess\Piece;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Board;

/**
 * AbstractPiece
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
abstract class AbstractPiece
{
    use PieceObserverBoardTrait;

    /**
     * The piece's color in PGN format.
     *
     * @var string
     */
    protected string $color;

    /**
     * The piece's square string.
     *
     * @var string
     */
    protected string $sq;

    /**
     * The piece's square object.
     *
     * @var \Chess\Variant\Classical\PGN\AN\Square
     */
    protected Square $square;

    /**
     * The piece's id in PGN format.
     *
     * @var string
     */
    protected string $id;

    /**
     * The piece's mobility.
     *
     * @var array
     */
    protected array $mobility;

    /**
     * The piece's next move.
     *
     * @var object
     */
    protected object $move;

    /**
     * The chessboard.
     *
     * @var \Chess\Variant\Classical\Board
     */
    protected Board $board;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param Square \Chess\Variant\Classical\PGN\AN\Square $square
     * @param string $id
     */
    public function __construct(string $color, string $sq, Square $square, string $id)
    {
        $this->color = $color;
        $this->sq = $sq;
        $this->square = $square;
        $this->id = $id;
    }

    /**
     * Returns the piece's legal moves.
     *
     * @return array
     */
    abstract public function sqs(): array;

    /**
     * Returns the squares defended by the piece.
     *
     * @return array|null
     */
    abstract public function defendedSqs(): ?array;

    /**
     * Returns the opponent's pieces that are being attacked by this piece.
     *
     * @return array|null
     */
    public function attackedPieces(): ?array
    {
        $attackedPieces = [];
        foreach ($sqs = $this->sqs() as $sq) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                if ($piece->getColor() === $this->oppColor()) {
                    $attackedPieces[] = $piece;
                }
            }
        }

        return $attackedPieces;
    }

    /**
     * Returns the opponent's pieces that attack this piece.
     *
     * @return array|null
     */
    public function attackingPieces(): ?array
    {
        $attackingPieces = [];
        foreach ($this->board->getPieces($this->oppColor()) as $piece) {
            if (in_array($this->sq, $piece->sqs())) {
                $attackingPieces[] = $piece;
            }
        }

        return $attackingPieces;
    }

    /**
     * Returns the pieces that are defending this piece.
     *
     * @return array|null
     */
    public function defendingPieces(): ?array
    {
        $defendingPieces = [];
        foreach ($this->board->getPieces($this->color) as $piece) {
            if (in_array($this->sq, $piece->defendedSqs())) {
                $defendingPieces[] = $piece;
            }
        }

        return $defendingPieces;
    }

    /**
     * Checks out if the opponent's king is attacked by the piece.
     *
     * @return bool
     */
    public function isAttackingKing(): bool
    {
        foreach ($this->attackedPieces() as $piece) {
            if ($piece->getId() === Piece::K) {
                return true;
            }
        }

        return false;
    }

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
     * Gets the piece's id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the piece's square.
     *
     * @return string
     */
    public function getSq(): string
    {
        return $this->sq;
    }

    /**
     * Gets the piece's file.
     *
     * @return string
     */
    public function getSqFile(): string
    {
        return $this->sq[0];
    }

    /**
     * Gets the piece's rank.
     *
     * @return int
     */
    public function getSqRank(): int
    {
        return (int) substr($this->sq, 1);
    }

    /**
     * Gets the piece's mobility.
     *
     * @return array|object
     */
    public function getMobility(): array|object
    {
        return $this->mobility;
    }

    /**
     * Gets the piece's move.
     *
     * @return object
     */
    public function getMove(): object
    {
        return $this->move;
    }

    /**
     * Sets the piece's next move.
     *
     * @param object $move
     */
    public function setMove(object $move): AbstractPiece
    {
        $this->move = $move;

        return $this;
    }

    /**
     * Gets the piece's opposite color.
     *
     * @return string
     */
    public function oppColor(): string
    {
        return $this->board->getColor()->opp($this->color);
    }

    /**
     * Checks whether or not the piece can be moved.
     *
     * @return boolean
     */
    public function isMovable(): bool
    {
        if ($this->move) {
            return in_array($this->move->sq->next, $this->sqs());
        }

        return false;
    }

    /**
     * Returns true if the piece is pinned.
     *
     * @return boolean
     */
    public function isPinned(): bool
    {
        $king = $this->board->getPiece($this->getColor(), Piece::K);
        $clone = unserialize(serialize($this->board));
        $clone->detach($clone->getPieceBySq($this->getSq()));
        $clone->refresh();
        $newKing = $clone->getPiece($this->getColor(), Piece::K);
        $diffPieces = $this->board->diffPieces($king->attackingPieces(), $newKing->attackingPieces());
        foreach ($diffPieces as $diffPiece) {
            if ($diffPiece->getColor() !== $king->getColor()) {
                return true;
            }
        }

        return false;
    }
}
