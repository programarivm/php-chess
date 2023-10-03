<?php

namespace Chess\Variant;

use Chess\Piece\P;
use Chess\Piece\RType;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

trait RandomStartPiecesTrait
{
    protected array $startPos;

    protected array $startPieces = [];

    protected array $size;

    public function create()
    {
        $longCastlingRook = null;

        foreach ($this->startPos as $key => $val) {
            $wSq = chr(97 + $key) . '1';
            $bSq = chr(97 + $key) . $this->size['ranks'];
            $className = "\\Chess\\Piece\\{$val}";
            if ($val !== Piece::R) {
                $this->startPieces[] =  new $className(Color::W, $wSq, $this->size);
                $this->startPieces[] =  new $className(Color::B, $bSq, $this->size);
            } elseif (!$longCastlingRook) {
                $this->startPieces[] =  new $className(Color::W, $wSq, $this->size, RType::CASTLE_LONG);
                $this->startPieces[] =  new $className(Color::B, $bSq, $this->size, RType::CASTLE_LONG);
                $longCastlingRook = $this->startPos[$key];
            } else {
                $this->startPieces[] =  new $className(Color::W, $wSq, $this->size, RType::CASTLE_SHORT);
                $this->startPieces[] =  new $className(Color::B, $bSq, $this->size, RType::CASTLE_SHORT);
            }
        }

        for ($i = 0; $i < $this->size['files']; $i++) {
            $wSq = chr(97 + $i) . 2;
            $bSq = chr(97 + $i) . $this->size['ranks'] - 1;
            $this->startPieces[] = new P(Color::W, $wSq, $this->size);
            $this->startPieces[] = new P(Color::B, $bSq, $this->size);
        }

        return $this->startPieces;
    }
}
