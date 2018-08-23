<?php
namespace PGNChess\Piece;

/**
 * Class that represents a bishop, a rook or a queen.
 *
 * These three pieces are quite similar. They can slide on the board, so to speak,
 * which means that their legal moves can be computed in the exact same way.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
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
    public function getLegalMoves(): array
    {
        $moves = [];
        foreach ($this->scope as $direction) {
            foreach ($direction as $square) {
                if (
                    !in_array($square, self::$boardStatus->squares->used->{$this->getColor()}) &&
                    !in_array($square, self::$boardStatus->squares->used->{$this->getOppositeColor()})
                ) {
                    $moves[] = $square;
                } elseif (in_array($square, self::$boardStatus->squares->used->{$this->getOppositeColor()})) {
                    $moves[] = $square;
                    break 1;
                } elseif (in_array($square, self::$boardStatus->squares->used->{$this->getColor()})) {
                    break 1;
                }
            }
        }

        return $moves;
    }
}
