<?php

namespace Chess\FEN;

use Chess\Exception\UnknownNotationException;

/**
 * Validation class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Validate
{
    public static function length(string $fen): bool
    {
        if (count(explode(' ', $fen)) === 6) {
            return true;
        }

        throw new UnknownNotationException(
            "The FEN string should consist of six fields separated by a space char."
        );
    }

    public static function castling(string $ability): bool
    {
        if ($ability) {
            if ('-' === $ability) {
                return true;
            } elseif (preg_match('/^K?Q?k?q?$/', $ability)) {
                return true;
            }
        }

        throw new UnknownNotationException(
            "This FEN string does not contain a valid castling ability."
        );
    }

    public static function fen(string $string): bool
    {
        return self::length($string) && self::castling($string);
    }
}
