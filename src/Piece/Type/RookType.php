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
    const CASTLE_SHORT = 'castling short';
    const CASTLE_LONG = 'castling long';
    const PROMOTED = 'promoted';
    const SLIDER = 'slider';

    public static function getChoices()
    {
        return [
            self::CASTLE_SHORT,
            self::CASTLE_LONG,
            self::PROMOTED,
            self::SLIDER
        ];
    }
}
