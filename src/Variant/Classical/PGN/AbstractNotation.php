<?php

namespace Chess\Variant\Classical\PGN;

use ReflectionClass;

/**
 * Abstract notation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractNotation
{
    public static function values(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }
}
