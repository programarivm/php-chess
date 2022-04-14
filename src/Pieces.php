<?php

namespace Chess;

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
 * Pieces.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Pieces
{
    /**
     * Pieces.
     *
     * @var array
     */
    protected array $pieces;

    public function getPieces()
    {
        return $this->pieces;
    }

    public function push(string $color, string $id, string $sq)
    {
        if ($id === Piece::K) {
            $this->pieces[] = new King($color, $sq);
        } elseif ($id === Piece::Q) {
            $this->pieces[] = new Queen($color, $sq);
        } elseif ($id === Piece::R) {
            if ($color === Color::B &&
                $sq === 'a8'
            ) {
                $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
            } elseif (
                $color === Color::B &&
                $sq === 'h8'
            ) {
                $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
            } elseif (
                $color === Color::W &&
                $sq === 'a1'
            ) {
                $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
            } elseif (
                $color === Color::W &&
                $sq === 'h1'
            ) {
                $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_SHORT);
            } else {
                // it really doesn't matter which RookType is assigned
                $this->pieces[] = new Rook($color, $sq, RookType::CASTLE_LONG);
            }
        } elseif ($id === Piece::B) {
            $this->pieces[] = new Bishop($color, $sq);
        } elseif ($id === Piece::N) {
            $this->pieces[] = new Knight($color, $sq);
        } elseif ($id === Piece::P) {
            $this->pieces[] = new Pawn($color, $sq);
        }
    }
}
