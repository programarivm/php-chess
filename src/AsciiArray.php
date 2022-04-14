<?php

namespace Chess;

use Chess\FEN\Field\CastlingAbility;

/**
 * Ascii array.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class AsciiArray
{
    /**
     * Array.
     *
     * @var array
     */
    private array $array;

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
     * Returns the array.
     *
     * @return array
     */
     public function getArray(): array
     {
         return $this->array;
     }

    /**
     * Returns a Chess\Board object.
     *
     * @param string $turn
     * @param \stdClass $castlingAbility
     * @return \Chess\Board
     */
    public function toBoard(
        string $turn,
        $castlingAbility = CastlingAbility::NEITHER
    ): Board
    {
        $pieces = (new Pieces())->ascii($this->array)->getPieces();
        $board = (new Board($pieces, $castlingAbility))->setTurn($turn);

        return $board;
    }

    /**
     * Sets an element in the array.
     *
     * @param string $piece
     * @param string $sq
     * @return \Chess\AsciiArray
     */
    public function setElem(string $piece, string $sq): AsciiArray
    {
        $index = self::fromAlgebraicToIndex($sq);
        $this->array[$index[0]][$index[1]] = $piece;

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
