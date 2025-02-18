<?php

namespace Chess\Variant;

use Chess\Variant\AbstractNotation;
use Chess\Variant\RType;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

abstract class AbstractRandomPieceArrayFactory
{
    protected static function pieces(array $shuffle, string $namespace, AbstractNotation $square): array
    {
        $pieces = [];
        $longCastlingRook = null;
        foreach ($shuffle as $key => $val) {
            $wSq = chr(97 + $key) . '1';
            $bSq = chr(97 + $key) . $square::SIZE['ranks'];
            $class = VariantType::getClass($val, $namespace);
            if ($val !== Piece::R) {
                $pieces[] =  new $class(Color::W, $wSq, $square);
                $pieces[] =  new $class(Color::B, $bSq, $square);
            } elseif (!$longCastlingRook) {
                $pieces[] =  new $class(Color::W, $wSq, $square, RType::CASTLE_LONG);
                $pieces[] =  new $class(Color::B, $bSq, $square, RType::CASTLE_LONG);
                $longCastlingRook = $shuffle[$key];
            } else {
                $pieces[] =  new $class(Color::W, $wSq, $square, RType::CASTLE_SHORT);
                $pieces[] =  new $class(Color::B, $bSq, $square, RType::CASTLE_SHORT);
            }
        }
        for ($i = 0; $i < $square::SIZE['files']; $i++) {
            $wSq = chr(97 + $i) . 2;
            $bSq = chr(97 + $i) . $square::SIZE['ranks'] - 1;
            $pieces[] = new P(Color::W, $wSq, $square);
            $pieces[] = new P(Color::B, $bSq, $square);
        }

        return $pieces;
    }
}
