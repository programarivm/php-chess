<?php

namespace Chess\Piece;

/**
 * Class that represents a bishop, a rook or a queen.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class Slider extends AbstractPiece
{
    /**
     * Constructor.
     *
     * @param $color
     * @param $square
     * @param $identity
     */
    public function __construct(string $color, string $square, string $identity)
    {
        parent::__construct($color, $square, $identity);
    }

    /**
     * Gets the legal moves of a slider piece.
     *
     * @return array The slider piece's (BRQ) legal moves.
     */
    public function getSquares(): array
    {
        $moves = [];
        foreach ($this->scope as $direction) {
            foreach ($direction as $square) {
                if (
                    !in_array($square, $this->board->getSquares()->used->{$this->getColor()}) &&
                    !in_array($square, $this->board->getSquares()->used->{$this->getOppColor()})
                ) {
                    $moves[] = $square;
                } elseif (in_array($square, $this->board->getSquares()->used->{$this->getOppColor()})) {
                    $moves[] = $square;
                    break 1;
                } elseif (in_array($square, $this->board->getSquares()->used->{$this->getColor()})) {
                    break 1;
                }
            }
        }

        return $moves;
    }

    /**
     * Gets the defended squares by a slider piece.
     *
     * @return array The slider piece's (BRQ) defended squares.
     */
    public function getDefendedSquares(): array
    {
        $squares = [];
        foreach ($this->scope as $direction) {
            foreach ($direction as $square) {
                if (in_array($square, $this->board->getSquares()->used->{$this->getColor()})) {
                    $squares[] = $square;
                    break 1;
                } elseif (in_array($square, $this->board->getSquares()->used->{$this->getOppColor()})) {
                    break 1;
                }
            }
        }

        return $squares;
    }
}
