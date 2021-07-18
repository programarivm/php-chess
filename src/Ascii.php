<?php

namespace Chess;

use Chess\PGN\Symbol;

/**
 * Ascii.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Ascii
{
    public function toArray(Board $board)
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
            $position = $piece->getPosition();
            $rank = $position[0];
            $file = $position[1] - 1;
            Symbol::WHITE === $piece->getColor()
                ? $array[$file][ord($rank)-97] = ' '.$piece->getIdentity().' '
                : $array[$file][ord($rank)-97] = ' '.strtolower($piece->getIdentity()).' ';
        }

        return $array;
    }

    public function toBoard(array $array, string $turn)
    {
        $pieces = [];
        foreach ($array as $key => $row) {
            $file = 'a';
            $rank = 8 - $key;
            foreach (str_split($row) as $char) {
                if (ctype_lower($char)) {
                    $char = strtoupper($char);
                    $this->pushPiece(Symbol::BLACK, $char, $file.$rank, $pieces);
                    $file = chr(ord($file) + 1);
                } elseif (ctype_upper($char)) {
                    $this->pushPiece(Symbol::WHITE, $char, $file.$rank, $pieces);
                    $file = chr(ord($file) + 1);
                } elseif (is_numeric($char)) {
                    $file = chr(ord($file) + $char);
                }
            }
        }
        $board = (new Board($this->pieces, $this->castling))
            ->setTurn($turn);

        return $board;
    }

    private function pushPiece($color, $char, $square, &$pieces)
    {
        switch ($char) {
            case Symbol::KING:
                $pieces[] = new King($color, $square);
                break;
            case Symbol::QUEEN:
                $pieces[] = new Queen($color, $square);
                break;
            case Symbol::ROOK:
                if ($color === Symbol::BLACK &&
                    $square === 'a8' &&
                    $this->castling[$color][Symbol::CASTLING_LONG]
                ) {
                    $pieces[] = new Rook($color, $square, RookType::CASTLING_LONG);
                } elseif (
                    $color === Symbol::BLACK &&
                    $square === 'h8' &&
                    $this->castling[$color][Symbol::CASTLING_SHORT]
                ) {
                    $pieces[] = new Rook($color, $square, RookType::CASTLING_SHORT);
                } elseif (
                    $color === Symbol::WHITE &&
                    $square === 'a1' &&
                    $this->castling[$color][Symbol::CASTLING_LONG]
                ) {
                    $pieces[] = new Rook($color, $square, RookType::CASTLING_LONG);
                } elseif (
                    $color === Symbol::WHITE &&
                    $square === 'h1' &&
                    $this->castling[$color][Symbol::CASTLING_SHORT]
                ) {
                    $pieces[] = new Rook($color, $square, RookType::CASTLING_SHORT);
                } else {
                    // in this case it really doesn't matter which RookType is assigned to the rook
                    $pieces[] = new Rook($color, $square, RookType::CASTLING_LONG);
                }
                break;
            case Symbol::BISHOP:
                $pieces[] = new Bishop($color, $square);
                break;
            case Symbol::KNIGHT:
                $pieces[] = new Knight($color, $square);
                break;
            case Symbol::PAWN:
                $pieces[] = new Pawn($color, $square);
                break;
            default:
                // do nothing
                break;
        }
        return $pieces;
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
}
