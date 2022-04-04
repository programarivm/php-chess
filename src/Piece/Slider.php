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
     * @param $sq
     * @param $id
     */
    public function __construct(string $color, string $sq, string $id)
    {
        parent::__construct($color, $sq, $id);
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
            foreach ($direction as $sq) {
                if (
                    !in_array($sq, $this->board->getSquares()->used->{$this->getColor()}) &&
                    !in_array($sq, $this->board->getSquares()->used->{$this->getOppColor()})
                ) {
                    $moves[] = $sq;
                } elseif (in_array($sq, $this->board->getSquares()->used->{$this->getOppColor()})) {
                    $moves[] = $sq;
                    break 1;
                } elseif (in_array($sq, $this->board->getSquares()->used->{$this->getColor()})) {
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
        $sqs = [];
        foreach ($this->scope as $direction) {
            foreach ($direction as $sq) {
                if (in_array($sq, $this->board->getSquares()->used->{$this->getColor()})) {
                    $sqs[] = $sq;
                    break 1;
                } elseif (in_array($sq, $this->board->getSquares()->used->{$this->getOppColor()})) {
                    break 1;
                }
            }
        }

        return $sqs;
    }
}
