<?php

namespace Chess\Array;

use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\Board;

/**
 * Ascii array.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class AsciiArray extends AbstractArray
{
    /**
     * Constructor.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * Returns a Chess\Board object.
     *
     * @param string $turn
     * @param string $castlingAbility
     * @return Board
     */
    public function toBoard(
        string $turn,
        string $castlingAbility = CastlingAbility::NEITHER
    ): Board
    {
        $pieces = (new PieceArray($this->array))->getArray();

        return (new Board($pieces, $castlingAbility))->setTurn($turn);
    }

    /**
     * Sets an element in the array using algebraic notation to identify the square.
     *
     * @param string $elem
     * @param string $sq
     * @return AsciiArray
     */
    public function setElem(string $elem, string $sq): AsciiArray
    {
        $index = self::fromAlgebraicToIndex($sq);
        $this->array[$index[0]][$index[1]] = $elem;

        return $this;
    }

    /**
     * Returns the array indexes of the given square.
     *
     * @param string $sq
     * @return array
     */
    public static function fromAlgebraicToIndex(string $sq): array
    {
        $j = ord($sq[0]) - 97;
        $i = (int) ltrim($sq, $sq[0]) - 1;

        return [
            $i,
            $j,
        ];
    }

    /**
     * Returns a square given the indexes of an array.
     *
     * @param int $i
     * @param int $j
     * @return string
     */
    public static function fromIndexToAlgebraic(int $i, int $j): string
    {
        $file = chr(97 + $i);
        $rank = $j + 1;

        return $file.$rank;
    }
}
