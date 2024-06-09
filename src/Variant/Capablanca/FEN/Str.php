<?php

namespace Chess\Variant\Capablanca\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Capablanca\FEN\Field\PiecePlacement;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Capablanca\Rule\CastlingRule;
use Chess\Variant\Classical\FEN\Str as ClassicalFenStr;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * FEN string.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class Str extends ClassicalFenStr
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

        (new PiecePlacement())->validate($fields[0]);

        (new Color())->validate($fields[1]);

        (new CastlingRule())->validate($fields[2]);

        if ('-' !== $fields[3]) {
            (new Square())->validate($fields[3]);
        }

        return $string;
    }
}
