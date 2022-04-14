<?php

namespace Chess\FEN\Field;

use Chess\Exception\UnknownNotationException;
use Chess\FEN\ValidationInterface;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;

/**
 * Castling ability.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class CastlingAbility implements ValidationInterface
{
    const START = 'KQkq';

    const NEITHER = '-';

    /**
     * String validation.
     *
     * @param string $value
     * @return string if the value is valid
     * @throws UnknownNotationException
     */
    public static function validate(string $value): string
    {
        if ($value === self::NEITHER) {
            return $value;
        } elseif ($value && preg_match('/^K?Q?k?q?$/', $value)) {
            return $value;
        }

        throw new UnknownNotationException;
    }

    /**
     * Removes the given castling ability.
     *
     * @param string $castlingAbility
     * @param string $color
     * @param array $ids
     * @return string
     */
    public static function remove(string $castlingAbility, string $color, array $ids): string
    {
        if ($color === Color::B) {
            $ids = array_map('mb_strtolower', $ids);
        }
        $castlingAbility = str_replace($ids, '', $castlingAbility);
        if (empty($castlingAbility)) {
            $castlingAbility = self::NEITHER;
        }

        return $castlingAbility;
    }

    /**
     * Castles the king.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return string
     */
    public static function castle(string $castlingAbility, string $color): string
    {
        $castlingAbility = self::remove(
            $castlingAbility,
            $color,
            [ Piece::K, Piece::Q ],
        );

        return $castlingAbility;
    }

    /**
     * Finds out if a long castling move can be made.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return string
     */
    public static function long(string $castlingAbility, string $color): string
    {
        $id = Piece::Q;
        if ($color === Color::B) {
            $id = mb_strtolower($id);
        }

        return strpbrk($castlingAbility, $id);
    }

    /**
     * Finds out if a short castling move can be made.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return string
     */
    public static function short(string $castlingAbility, string $color)
    {
        $id = Piece::K;
        if ($color === Color::B) {
            $id = mb_strtolower($id);
        }

        return strpbrk($castlingAbility, $id);
    }

    /**
     * Finds out if a castling move can be made.
     *
     * @param string $castlingAbility
     * @param string $color
     * @return string
     */
    public static function can(string $castlingAbility, string $color)
    {
        return self::long($castlingAbility, $color) ||
            self::short($castlingAbility, $color);
    }
}
