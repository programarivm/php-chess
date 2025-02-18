<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\AbstractRandomPieceArrayFactory;
use Chess\Variant\Capablanca\PGN\Square;

class RandomPieceArrayFactory extends AbstractRandomPieceArrayFactory
{
    public static function create(array $shuffle): array
    {
        $namespace = 'Capablanca';

        $square = new Square();

        return self::pieces($shuffle, $namespace, $square);
    }
}
