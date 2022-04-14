<?php

namespace Chess\Array;

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
 * Piece array.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class PieceArray extends AbstractArray
{
    /**
     * Constructor.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        foreach ($array as $i => $row) {
            $file = 'a';
            $rank = $i + 1;
            foreach ($row as $j => $item) {
                $char = trim($item);
                if (ctype_lower($char)) {
                    $char = strtoupper($char);
                    $this->push(Color::B, $char, $file.$rank);
                } elseif (ctype_upper($char)) {
                    $this->push(Color::W, $char, $file.$rank);
                }
                $file = chr(ord($file) + 1);
            }
        }

        return $this;
    }

    /**
     * Pushes an element into the array.
     *
     * @param string $color
     * @param string $id
     * @param string $sq
     */
    private function push(string $color, string $id, string $sq): void
    {
        if ($id === Piece::K) {
            $this->array[] = new King($color, $sq);
        } elseif ($id === Piece::Q) {
            $this->array[] = new Queen($color, $sq);
        } elseif ($id === Piece::R) {
            if ($color === Color::B && $sq === 'a8') {
                $this->array[] = new Rook($color, $sq, RookType::CASTLE_LONG);
            } elseif ($color === Color::B && $sq === 'h8') {
                $this->array[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
            } elseif ($color === Color::W && $sq === 'a1') {
                $this->array[] = new Rook($color, $sq, RookType::CASTLE_LONG);
            } elseif ($color === Color::W && $sq === 'h1') {
                $this->array[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
            } else { // it doesn't matter which RookType is assigned
                $this->array[] = new Rook($color, $sq, RookType::CASTLE_LONG);
            }
        } elseif ($id === Piece::B) {
            $this->array[] = new Bishop($color, $sq);
        } elseif ($id === Piece::N) {
            $this->array[] = new Knight($color, $sq);
        } elseif ($id === Piece::P) {
            $this->array[] = new Pawn($color, $sq);
        }
    }
}
