<?php

namespace Chess\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\Validate as PgnValidate;

/**
 * Validation class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Validate
{
    public static function length(array $fields): bool
    {
        if (count($fields) === 6) {
            return true;
        }

        throw new UnknownNotationException(
            "The FEN string should consist of six fields separated by a space char."
        );
    }

    public static function color(string $color): string
    {
        return PgnValidate::color($color);
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

    public static function square(string $square): string
    {
        if ('-' === $square) {
            return $square;
        }

        return PgnValidate::square($square);
    }

    public static function fen(string $string): bool
    {
        $fields = explode(' ', $string);

        return self::length($fields) &&
            self::color($fields[1]) &&
            self::castling($fields[2]) &&
            self::square($fields[3]);
    }
}
