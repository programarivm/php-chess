<?php

namespace Chess\Piece;

class VariantType
{
    const CAPABLANCA = 'Capablanca';
    const CLASSICAL = 'Classical';

    public static function getClass(string $variant, string $name)
    {
        $class = "\\Chess\\Piece\\{$variant}\\{$name}";
        if (class_exists($class)) {
            return $class;
        }

        return "\\Chess\\Piece\\{$name}";
    }
}
