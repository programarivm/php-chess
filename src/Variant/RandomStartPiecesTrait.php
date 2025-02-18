<?php

namespace Chess\Variant;

use Chess\Variant\RType;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

trait RandomStartPiecesTrait
{
    protected array $shuffle;

    protected array $pieces = [];

    public function pieces(): array
    {
        $longCastlingRook = null;

        foreach ($this->shuffle as $key => $val) {
            $wSq = chr(97 + $key) . '1';
            $bSq = chr(97 + $key) . $this->square::SIZE['ranks'];
            $class = VariantType::getClass($val, $this->namespace);
            if ($val !== Piece::R) {
                $this->pieces[] =  new $class(Color::W, $wSq, $this->square);
                $this->pieces[] =  new $class(Color::B, $bSq, $this->square);
            } elseif (!$longCastlingRook) {
                $this->pieces[] =  new $class(Color::W, $wSq, $this->square, RType::CASTLE_LONG);
                $this->pieces[] =  new $class(Color::B, $bSq, $this->square, RType::CASTLE_LONG);
                $longCastlingRook = $this->shuffle[$key];
            } else {
                $this->pieces[] =  new $class(Color::W, $wSq, $this->square, RType::CASTLE_SHORT);
                $this->pieces[] =  new $class(Color::B, $bSq, $this->square, RType::CASTLE_SHORT);
            }
        }

        for ($i = 0; $i < $this->square::SIZE['files']; $i++) {
            $wSq = chr(97 + $i) . 2;
            $bSq = chr(97 + $i) . $this->square::SIZE['ranks'] - 1;
            $this->pieces[] = new P(Color::W, $wSq, $this->square);
            $this->pieces[] = new P(Color::B, $bSq, $this->square);
        }

        return $this->pieces;
    }
}
