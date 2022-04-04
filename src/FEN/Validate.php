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
    public static function pieces(string $placement): string
    {
        $fields = explode('/', $placement);

        if ($count = count($fields) === 8) {
            return $placement;
        }

        throw new UnknownNotationException(
            "The FEN string should contain a valid piece placement."
        );
    }

    public static function color(string $color): string
    {
        return PgnValidate::color($color);
    }

    public static function castling(string $ability): string
    {
        if ($ability) {
            if ('-' === $ability || preg_match('/^K?Q?k?q?$/', $ability)) {
                return $ability;
            }
        }

        throw new UnknownNotationException(
            "This FEN string does not contain a valid castling ability."
        );
    }

    public static function sq(string $sq): string
    {
        if ('-' === $sq) {
            return $sq;
        }

        return PgnValidate::sq($sq);
    }

    public static function fen(string $string): string
    {
        $fields = explode(' ', $string);

        self::pieces($fields[0]);
        self::color($fields[1]);
        self::castling($fields[2]);
        self::sq($fields[3]);

        return $string;
    }
}
