<?php

namespace Chess\Variant;

class VariantType
{
    const CAPABLANCA = 'capablanca';

    const CAPABLANCA_FISCHER = 'capablanca-fischer';
    
    const CHESS_960 = '960';
    
    const CLASSICAL = 'classical';

    const DUNSANY = 'dunsany';

    const LOSING = 'losing';

    const RACING_KINGS = 'racing-kings';

    public static function getClass(string $filename, string $namespace = '')
    {
        if ($namespace) {
            $class = "\\Chess\\Variant\\{$namespace}\\{$filename}";
            if (class_exists($class)) {
                return $class;
            }
        }

        return "\\Chess\\Variant\\Classical\\{$filename}";
    }
}
