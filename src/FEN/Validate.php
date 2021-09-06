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
        $exploded = explode(' ', $fen);
        if (count($exploded) !== 6) {
            throw new UnknownNotationException(
                "This FEN string does not consist of six fields separated by a space char."
            );
        }

        return true;
    }

    public static function castling(string $ability): bool
    {
        if (!preg_match('/^K?Q?k?q?$/', $ability)) {
            throw new UnknownNotationException(
                "This FEN string does not contain a valid castling ability."
            );
        }

        return true;
    }
}
