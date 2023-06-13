<?php

namespace Chess\Variant\Capablanca\FEN\Field;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\Field\PiecePlacement as ClassicalFenPiecePlacement;

/**
 * Piece placement.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class PiecePlacement extends ClassicalFenPiecePlacement
{
    /**
     * String validation.
     *
     * @param string $value
     * @return string if the value is valid
     * @throws UnknownNotationException
     */
    public static function validate(string $value): string
    {
        $fields = explode('/', $value);

        if (
            self::eightFields($fields) &&
            self::twoKings($fields) &&
            self::validChars($fields)
        ) {
            return $value;
        }

        throw new UnknownNotationException();
    }

    /**
     * Checks out that the pieces are valid.
     *
     * @param array $fields
     * @return bool
     */
    protected static function validChars(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match("#^[rnbqkpacRNBQKPAC0-9]+$#", $field)) {
                return false;
            }
        }

        return true;
    }
}
