<?php

namespace Chess\Piece;

/**
 * RookType.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class RookType
{
    const CASTLE_SHORT = 'castle short';
    const CASTLE_LONG = 'castle long';
    const PROMOTED = 'promoted';
    const SLIDER = 'slider';

    public static function all(): array
    {
        return [
            self::CASTLE_SHORT,
            self::CASTLE_LONG,
            self::PROMOTED,
            self::SLIDER
        ];
    }
}
