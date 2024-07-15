<?php

namespace Chess\Variant;

class VariantType
{
    const CAPABLANCA = 'capablanca';

    const CLASSICAL = 'classical';

    const LOSING = 'losing';

    public static function getClass(string $pieceVariant, string $name)
    {
        $namespace = ucfirst($pieceVariant);
        $class = "\\Chess\\Variant\\{$namespace}\\Piece\\{$name}";
        if (class_exists($class)) {
            return $class;
        }

        return "\\Chess\\Variant\\Classical\\Piece\\{$name}";
    }
}
