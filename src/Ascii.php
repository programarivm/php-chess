<?php

namespace Chess;

use Chess\Pieces;
use Chess\FEN\Field\CastlingAbility;
use Chess\PGN\AN\Color;

/**
 * Ascii
 *
 * The methods in this class can be used to convert Chess\Board objects into
 * character-based representations such as strings or arrays, and vice versa.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Ascii
{
    /**
     * Returns an ASCII array given a Chess\Board object.
     *
     * @param \Chess\Board $board
     * @param bool $flip
     * @return array
     */
    public static function toArray(Board $board, bool $flip = false): array
    {
        $array = [
            7 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            6 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            5 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            4 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            3 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            2 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            1 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
            0 => [' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . '],
        ];

        foreach ($board->getPieces() as $piece) {
            $position = $piece->getSquare();
            list($file, $rank) = self::fromAlgebraicToIndex($position);
            if ($flip) {
                $file = 7 - $file;
                $rank = 7 - $rank;
            }
            Color::W === $piece->getColor()
                ? $array[$file][$rank] = ' ' . $piece->getId() . ' '
                : $array[$file][$rank] = ' ' . strtolower($piece->getId()) . ' ';
        }

        return $array;
    }

    /**
     * Returns a Chess\Board object given an ASCII array.
     *
     * @param array $array
     * @param string $turn
     * @param \stdClass $castlingAbility
     * @return \Chess\Board
     */
    public static function toBoard(
        array $array,
        string $turn,
        $castlingAbility = CastlingAbility::NEITHER
    ): Board
    {
        $pieces = new Pieces();
        foreach ($array as $i => $row) {
            $file = 'a';
            $rank = $i + 1;
            foreach ($row as $j => $item) {
                $char = trim($item);
                if (ctype_lower($char)) {
                    $char = strtoupper($char);
                    $pieces->push(Color::B, $char, $file.$rank);
                } elseif (ctype_upper($char)) {
                    $pieces->push(Color::W, $char, $file.$rank);
                }
                $file = chr(ord($file) + 1);
            }
        }
        $board = (new Board($pieces->getPieces(), $castlingAbility))->setTurn($turn);

        return $board;
    }

    /**
     * Returns an ASCII string given a Chess\Board object.
     *
     * @param \Chess\Board $board
     * @return string
     */
    public static function toString(Board $board): string
    {
        $ascii = '';
        $array = self::toArray($board);
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $file) {
                $ascii .= $array[$i][$j];
            }
            $ascii .= PHP_EOL;
        }

        return $ascii;
    }

    /**
     * Sets a piece in a specific square given an ASCII array.
     *
     * @param string $piece
     * @param string $sq
     * @param array $array
     * @return \Chess\Ascii
     */
    public static function setArrayElem(string $piece, string $sq, &$array): Ascii
    {
        $index = self::fromAlgebraicToIndex($sq);
        $array[$index[0]][$index[1]] = $piece;

        return new static();
    }

    /**
     * Returns the ASCII array indexes of a square described in algebraic notation.
     *
     * @param string $sq
     * @return array
     */
    private static function fromAlgebraicToIndex(string $sq): array
    {
        $i = $sq[1] - 1;
        $j = ord($sq[0]) - 97;

        return [
            $i,
            $j,
        ];
    }
}
