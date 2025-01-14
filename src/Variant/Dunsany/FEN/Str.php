<?php

namespace Chess\Variant\Dunsany\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\Str as ClassicalStr;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\CastlingRule;
use Chess\Variant\Dunsany\FEN\PiecePlacement;

class Str extends ClassicalStr
{
    public function validate(string $string): string
    {
        $fields = explode(' ', $string);

        if (!isset($fields[0]) ||
            !isset($fields[1]) ||
            !isset($fields[2]) ||
            !isset($fields[3])
        ) {
            throw new UnknownNotationException();
        }

        (new PiecePlacement())->validate($fields[0]);

        (new Color())->validate($fields[1]);

        (new CastlingRule())->validate($fields[2]);

        if ('-' !== $fields[3]) {
            (new Square())->validate($fields[3]);
        }

        return $string;
    }
}
