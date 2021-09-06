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
    /**
     * Validates tne number of fields.
     *
     * @param string $fen
     * @return bool
     * @throws UnknownNotationException
     */
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
}
