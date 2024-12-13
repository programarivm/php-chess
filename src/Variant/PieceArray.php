<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Piece\R;
use Chess\Variant\Classical\Rule\CastlingRule;

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

        for ($i = count($array) - 1; $i >= 0; $i--) {
            for ($j = 0; $j < count($array[$i]); $j++) {
                $this->push(trim($array[$i][$j]), $this->square->toAlgebraic($j, $i));
            }
        }
    }

    private function push(string $id, string $sq): void
    {
        if (ctype_lower($id)) {
            $color = Color::B;
        } elseif (ctype_upper($id)) {
            $color = Color::W;
        } else {
            return;
        }

        $id = strtoupper($id);

        if ($id === Piece::R) {
            if ($sq === $this->castlingRule?->rule[$color][Piece::R][Castle::LONG]['from']) {
                $this->pieces[] = new R($color, $sq, $this->square, RType::CASTLE_LONG);
            } elseif ($sq === $this->castlingRule?->rule[$color][Piece::R][Castle::SHORT]['from']) {
                $this->pieces[] = new R($color, $sq, $this->square, RType::CASTLE_SHORT);
            } else {
                $this->pieces[] = new R($color, $sq, $this->square, RType::R);
            }
        } else {
            $class = VariantType::getClass($this->variant, $id);
            $this->pieces[] = new $class($color, $sq, $this->square);
        }
    }
}
