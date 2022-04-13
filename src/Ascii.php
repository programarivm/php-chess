<?php

namespace Chess;

use Chess\CastlingRule;
use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Piece\Bishop;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Queen;
use Chess\Piece\Rook;
use Chess\Piece\RookType;

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
    public static function toBoard(array $array, string $turn, $castlingAbility = null): Board
    {
        if (!$castlingAbility) {
            $castlingAbility = CastlingAbility::NEITHER;
        }
        $pieces = [];
        foreach ($array as $i => $row) {
            $file = 'a';
            $rank = $i + 1;
            foreach ($row as $j => $item) {
                $char = trim($item);
                if (ctype_lower($char)) {
                    $char = strtoupper($char);
                    self::pushPiece(Color::B, $char, $file.$rank, $castlingAbility, $pieces);
                } elseif (ctype_upper($char)) {
                    self::pushPiece(Color::W, $char, $file.$rank, $castlingAbility, $pieces);
                }
                $file = chr(ord($file) + 1);
            }
        }
        $board = (new Board($pieces, $castlingAbility))->setTurn($turn);

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

    /**
     * Returns the square in algebraic notation corresponding to the given ASCII array indexes.
     *
     * @param int $i
     * @param int $j
     * @return string
     */
    private static function fromIndexToAlgebraic(int $i, int $j): string
    {
        $file = chr(97 + $j);
        $rank = $i + 1;

        return $file.$rank;
    }

    private static function pushPiece($color, $char, $sq, $castle, &$pieces): void
    {
        switch ($char) {
            case Piece::K:
                $pieces[] = new King($color, $sq);
                break;
            case Piece::Q:
                $pieces[] = new Queen($color, $sq);
                break;
            case Piece::R:
                if ($color === Color::B &&
                    $sq === 'a8' &&
                    $castle[$color][Castle::LONG]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
                } elseif (
                    $color === Color::B &&
                    $sq === 'h8' &&
                    $castle[$color][Castle::SHORT]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
                } elseif (
                    $color === Color::W &&
                    $sq === 'a1' &&
                    $castle[$color][Castle::LONG]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
                } elseif (
                    $color === Color::W &&
                    $sq === 'h1' &&
                    $castle[$color][Castle::SHORT]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
                } else {
                    // in this case it really doesn't matter which RookType is assigned to the rook
                    $pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
                }
                break;
            case Piece::B:
                $pieces[] = new Bishop($color, $sq);
                break;
            case Piece::N:
                $pieces[] = new Knight($color, $sq);
                break;
            case Piece::P:
                $pieces[] = new Pawn($color, $sq);
                break;
            default:
                // do nothing
                break;
        }
    }
}
