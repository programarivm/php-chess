<?php

namespace Chess\Variant;

use Chess\Piece\RType;
use Chess\Piece\VariantType;
use Chess\Piece\Classical\P;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

trait RandomStartPiecesTrait
{
    protected string $variant;

    protected array $startPos;

    protected array $startPieces = [];

    public function create()
    {
        $longCastlingRook = null;

        foreach ($this->startPos as $key => $val) {
            $wSq = chr(97 + $key) . '1';
            $bSq = chr(97 + $key) . $this->square::SIZE['ranks'];
            $class = VariantType::getClass($this->variant, $val);
            if ($val !== Piece::R) {
                $this->startPieces[] =  new $class(Color::W, $wSq, $this->square);
                $this->startPieces[] =  new $class(Color::B, $bSq, $this->square);
            } elseif (!$longCastlingRook) {
                $this->startPieces[] =  new $class(Color::W, $wSq, $this->square, RType::CASTLE_LONG);
                $this->startPieces[] =  new $class(Color::B, $bSq, $this->square, RType::CASTLE_LONG);
                $longCastlingRook = $this->startPos[$key];
            } else {
                $this->startPieces[] =  new $class(Color::W, $wSq, $this->square, RType::CASTLE_SHORT);
                $this->startPieces[] =  new $class(Color::B, $bSq, $this->square, RType::CASTLE_SHORT);
            }
        }

        for ($i = 0; $i < $this->square::SIZE['files']; $i++) {
            $wSq = chr(97 + $i) . 2;
            $bSq = chr(97 + $i) . $this->square::SIZE['ranks'] - 1;
            $this->startPieces[] = new P(Color::W, $wSq, $this->square);
            $this->startPieces[] = new P(Color::B, $bSq, $this->square);
        }

        return $this->startPieces;
    }
}
