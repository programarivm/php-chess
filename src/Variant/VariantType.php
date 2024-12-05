<?php

namespace Chess\Variant;

class VariantType
{
    const CAPABLANCA = 'capablanca';

    const CLASSICAL = 'classical';

    const DUNSANY = 'dunsany';

    const LOSING = 'losing';

    const RACING_KINGS = 'racing-kings';

    public static function getClass(string $variant, string $name)
    {
        $namespace = ucfirst($variant);
        $class = "\\Chess\\Variant\\{$namespace}\\Piece\\{$name}";
        if (class_exists($class)) {
            return $class;
        }

        return "\\Chess\\Variant\\Classical\\Piece\\{$name}";
    }
}
