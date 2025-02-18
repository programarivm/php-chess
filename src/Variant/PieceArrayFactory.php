<?php

namespace Chess\Variant;

use Chess\Variant\AbstractNotation;
use Chess\Variant\Classical\R;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\CastlingRule;

class PieceArrayFactory
{
    public static function create(
        array $array, 
        AbstractNotation $square, 
        CastlingRule $castlingRule = null, 
        string $namespace
    ): array
    {
        $pieces = [];
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $char) {
                if ($char !== '.') {
                    $sq = $square->toAn($j, $i);
                    if (ctype_lower($char)) {
                        $color = Color::B;
                        $char = strtoupper($char);
                    } else {
                        $color = Color::W;
                    }
                    if ($char === Piece::R) {
                        if ($sq === $castlingRule?->rule[$color][Piece::R][Castle::LONG]['from']) {
                            $pieces[] = new R($color, $sq, $square, RType::CASTLE_LONG);
                        } elseif ($sq === $castlingRule?->rule[$color][Piece::R][Castle::SHORT]['from']) {
                            $pieces[] = new R($color, $sq, $square, RType::CASTLE_SHORT);
                        } else {
                            $pieces[] = new R($color, $sq, $square, RType::R);
                        }
                    } else {
                        $class = VariantType::getClass($char, $namespace);
                        $pieces[] = new $class($color, $sq, $square);
                    }
                }
            }
        }

        return $pieces;
    }
}
