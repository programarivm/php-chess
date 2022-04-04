<?php

namespace Chess;

use Chess\Castling;
use Chess\PGN\Symbol;
use Chess\Piece\Bishop;
use Chess\Piece\King;
use Chess\Piece\Knight;
use Chess\Piece\Pawn;
use Chess\Piece\Piece;
use Chess\Piece\Queen;
use Chess\Piece\Rook;
use Chess\Piece\Type\RookType;

/**
 * Ascii.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Ascii
{
    public function toArray(Board $board, bool $flip = false): array
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
            list($file, $rank) = $this->fromAlgebraicToIndex($position);
            if ($flip) {
                $file = 7 - $file;
                $rank = 7 - $rank;
            }
            Symbol::WHITE === $piece->getColor()
                ? $array[$file][$rank] = ' ' . $piece->getId() . ' '
                : $array[$file][$rank] = ' ' . strtolower($piece->getId()) . ' ';
        }

        return $array;
    }

    public function toBoard(array $array, string $turn, $castling = null): Board
    {
        if (!$castling) {
            $castling = [
                Symbol::WHITE => [
                    Castling::IS_CASTLED => false,
                    Symbol::CASTLING_SHORT => false,
                    Symbol::CASTLING_LONG => false,
                ],
                Symbol::BLACK => [
                    Castling::IS_CASTLED => false,
                    Symbol::CASTLING_SHORT => false,
                    Symbol::CASTLING_LONG => false,
                ],
            ];
        }
        $pieces = [];
        foreach ($array as $i => $row) {
            $file = 'a';
            $rank = $i + 1;
            foreach ($row as $j => $item) {
                $char = trim($item);
                if (ctype_lower($char)) {
                    $char = strtoupper($char);
                    $this->pushPiece(Symbol::BLACK, $char, $file.$rank, $castling, $pieces);
                } elseif (ctype_upper($char)) {
                    $this->pushPiece(Symbol::WHITE, $char, $file.$rank, $castling, $pieces);
                }
                $file = chr(ord($file) + 1);
            }
        }
        $board = (new Board($pieces, $castling))->setTurn($turn);

        return $board;
    }

    public function print(Board $board): string
    {
        $ascii = '';
        $array = $this->toArray($board);
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $file) {
                $ascii .= $array[$i][$j];
            }
            $ascii .= PHP_EOL;
        }

        return $ascii;
    }

    public function fromAlgebraicToIndex(string $sq): array
    {
        $i = $sq[1] - 1;
        $j = ord($sq[0]) - 97;

        return [
            $i,
            $j,
        ];
    }

    public function fromIndexToAlgebraic(int $i, int $j): string
    {
        $file = chr(97 + $j);
        $rank = $i + 1;

        return $file.$rank;
    }

    public function setArrayElem(string $piece, string $sq, &$array): Ascii
    {
        $index = $this->fromAlgebraicToIndex($sq);
        $array[$index[0]][$index[1]] = $piece;

        return $this;
    }

    private function pushPiece($color, $char, $sq, $castling, &$pieces)
    {
        switch ($char) {
            case Symbol::KING:
                $pieces[] = new King($color, $sq);
                break;
            case Symbol::QUEEN:
                $pieces[] = new Queen($color, $sq);
                break;
            case Symbol::ROOK:
                if ($color === Symbol::BLACK &&
                    $sq === 'a8' &&
                    $castling[$color][Symbol::CASTLING_LONG]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLING_LONG);
                } elseif (
                    $color === Symbol::BLACK &&
                    $sq === 'h8' &&
                    $castling[$color][Symbol::CASTLING_SHORT]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLING_SHORT);
                } elseif (
                    $color === Symbol::WHITE &&
                    $sq === 'a1' &&
                    $castling[$color][Symbol::CASTLING_LONG]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLING_LONG);
                } elseif (
                    $color === Symbol::WHITE &&
                    $sq === 'h1' &&
                    $castling[$color][Symbol::CASTLING_SHORT]
                ) {
                    $pieces[] = new Rook($color, $sq, RookType::CASTLING_SHORT);
                } else {
                    // in this case it really doesn't matter which RookType is assigned to the rook
                    $pieces[] = new Rook($color, $sq, RookType::CASTLING_LONG);
                }
                break;
            case Symbol::BISHOP:
                $pieces[] = new Bishop($color, $sq);
                break;
            case Symbol::KNIGHT:
                $pieces[] = new Knight($color, $sq);
                break;
            case Symbol::PAWN:
                $pieces[] = new Pawn($color, $sq);
                break;
            default:
                // do nothing
                break;
        }

        return $pieces;
    }
}
