<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\AbstractRandomPieceArrayFactory;
use Chess\Variant\Classical\PGN\Square;

class RandomPieceArrayFactory extends AbstractRandomPieceArrayFactory
{
    public static function create(array $shuffle): array
    {
        $namespace = 'Classical';

        $square = new Square();

        return self::pieces($shuffle, $namespace, $square);
    }
}
