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
    private array $array;

    private Square $square;

    private ?CastlingRule $castlingRule;

    private string $variant;

    public function __construct(array $array, Square $square, CastlingRule $castlingRule = null, string $variant)
    {
        $this->square = $square;
        $this->castlingRule = $castlingRule;
        $this->variant = $variant;

        for ($i = count($array) - 1; $i >= 0; $i--) {
            for ($j = 0; $j < count($array[$i]); $j++) {
                $char = trim($array[$i][$j]);
                if (ctype_lower($char)) {
                    $this->push(Color::B, strtoupper($char), $this->square->toAlgebraic($j, $i));
                } elseif (ctype_upper($char)) {
                    $this->push(Color::W, $char, $this->square->toAlgebraic($j, $i));
                }
            }
        }
    }

    public function getArray(): array
    {
        return $this->array;
    }

    private function push(string $color, string $id, string $sq): void
    {
        if ($id === Piece::R) {
            if ($sq === $this->castlingRule?->rule[$color][Piece::R][Castle::LONG]['from']) {
                $this->array[] = new R($color, $sq, $this->square, RType::CASTLE_LONG);
            } elseif ($sq === $this->castlingRule?->rule[$color][Piece::R][Castle::SHORT]['from']) {
                $this->array[] = new R($color, $sq, $this->square, RType::CASTLE_SHORT);
            } else {
                // it doesn't matter which RType is assigned
                $this->array[] = new R($color, $sq, $this->square, RType::R);
            }
        } else {
            $class = VariantType::getClass($this->variant, $id);
            $this->array[] = new $class($color, $sq, $this->square);
        }
    }
}
