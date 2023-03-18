<?php

namespace Chess\Piece;

use Chess\Piece\PieceArray;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\Board;

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
    protected array $array;

    /**
     * Size.
     *
     * @var array
     */
    private array $size;

    /**
     * Castling rule.
     *
     * @var array
     */
     private array $castlingRule;

    /**
     * Constructor.
     *
     * @param array $array
     * @param array $size
     * @param array $castlingRule
     */
    public function __construct(array $array, array $size, array $castlingRule)
    {
        $this->array = $array;
        $this->size = $size;
        $this->castlingRule = $castlingRule;
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
     * Returns a \Chess\Variant\Classical\Board object.
     *
     * @param string $className
     * @param string $turn
     * @param string $castlingAbility
     * @return \Chess\Variant\Classical\Board
     */
    public function toClassicalBoard(string $className, string $turn, string $castlingAbility = null): Board
    {
        $board = new $className();

        $pieces = (new PieceArray(
            $this->array,
            $board->getSize(),
            $board->getCastlingRule()
        ))->getArray();

        if (!$castlingAbility) {
            $castlingAbility = CastlingAbility::START;
        }

        $newBoard = (new $className($pieces, $castlingAbility))->setTurn($turn);

        return $newBoard;
    }

    /**
     * Returns a \Chess\Variant\Chess960\Board object.
     *
     * @param string $className
     * @param string $turn
     * @param string $castlingAbility
     * @param string $startPos
     * @return \Chess\Variant\Chess960\Board
     */
    public function toChess960Board(
        string $className,
        string $turn,
        string $castlingAbility = null,
        array $startPos
    ): Board
    {
        $board = new $className();

        $pieces = (new PieceArray(
            $this->array,
            $board->getSize(),
            $board->getCastlingRule()
        ))->getArray();

        if (!$castlingAbility) {
            $castlingAbility = CastlingAbility::START;
        }

        $newBoard = (new $className($startPos, $pieces, $castlingAbility))
            ->setTurn($turn);

        return $newBoard;
    }

    /**
     * Sets an element in the array using algebraic notation to identify the square.
     *
     * @param string $elem
     * @param string $sq
     * @return \Chess\Piece\AsciiArray
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
        $i = intval(ltrim($sq, $sq[0])) - 1;

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
