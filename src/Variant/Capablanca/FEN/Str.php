<?php

namespace Chess\Variant\Capablanca\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Capablanca\FEN\Field\PiecePlacement;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * FEN string.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class Str
{
    /**
     * String validation.
     *
     * @param string $string
     * @return string if the value is valid
     * @throws \Chess\Exception\UnknownNotationException
     */
    public function validate(string $string): string
    {
        $fields = explode(' ', $string);

        if (
            !isset($fields[0]) ||
            !isset($fields[1]) ||
            !isset($fields[2]) ||
            !isset($fields[3])
        ) {
            throw new UnknownNotationException();
        }

        PiecePlacement::validate($fields[0]);

        // side to move
        Color::validate($fields[1]);

        CastlingAbility::validate($fields[2]);

        // en passant square
        if ('-' !== $fields[3]) {
            (new Square())->validate($fields[3]);
        }

        return $string;
    }
}
