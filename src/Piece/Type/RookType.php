<?php
namespace Chess\Piece\Type;

/**
 * RookType class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class RookType
{
    const CASTLING_SHORT = 'castling short';
    const CASTLING_LONG = 'castling long';
    const PROMOTED = 'promoted';
    const SLIDER = 'slider';

    public static function getChoices()
    {
        return [
            self::CASTLING_SHORT,
            self::CASTLING_LONG,
            self::PROMOTED,
            self::SLIDER
        ];
    }
}
