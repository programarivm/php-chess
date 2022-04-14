<?php

namespace Chess\Array;

use Chess\Board;
use Chess\FEN\Field\CastlingAbility;

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
     * @return \Chess\Board
     */
    public function toBoard(
        string $turn,
        $castlingAbility = CastlingAbility::NEITHER
    ): Board
    {
        $pieces = (new PieceArray($this->array))->getArray();
        $board = (new Board($pieces, $castlingAbility))->setTurn($turn);

        return $board;
    }

    /**
     * Sets an element in the array using algebraic notation to identify the square.
     *
     * @param string $elem
     * @param string $sq
     * @return \Chess\Array\AsciiArray
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
        $i = $sq[1] - 1;
        $j = ord($sq[0]) - 97;

        return [
            $i,
            $j,
        ];
    }
}
