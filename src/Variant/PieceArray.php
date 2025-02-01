<?php

namespace Chess\Variant;

use Chess\Variant\Classical\R;
use Chess\Variant\Classical\PGN\Castle;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\CastlingRule;

class PieceArray
{
    public Square $square;

    public ?CastlingRule $castlingRule;

    public string $variant;

    public array $pieces;

    public function __construct(array $array, Square $square, CastlingRule $castlingRule = null, string $variant)
    {
        $this->square = $square;
        $this->castlingRule = $castlingRule;
        $this->variant = $variant;

        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $char) {
                if ($char !== '.') {
                    $sq = $this->square->toAn($j, $i);
                    if (ctype_lower($char)) {
                        $color = Color::B;
                        $char = strtoupper($char);
                    } else {
                        $color = Color::W;
                    }
                    if ($char === Piece::R) {
                        if ($sq === $this->castlingRule?->rule[$color][Piece::R][Castle::LONG]['from']) {
                            $this->pieces[] = new R($color, $sq, $this->square, RType::CASTLE_LONG);
                        } elseif ($sq === $this->castlingRule?->rule[$color][Piece::R][Castle::SHORT]['from']) {
                            $this->pieces[] = new R($color, $sq, $this->square, RType::CASTLE_SHORT);
                        } else {
                            $this->pieces[] = new R($color, $sq, $this->square, RType::R);
                        }
                    } else {
                        $class = VariantType::getClass($this->variant, $char);
                        $this->pieces[] = new $class($color, $sq, $this->square);
                    }
                }
            }
        }
    }
}
