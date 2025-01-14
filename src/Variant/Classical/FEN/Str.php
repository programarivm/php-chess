<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\PiecePlacement;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\CastlingRule;

class Str
{
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

        (new PiecePlacement())->validate($fields[0]);

        (new Color())->validate($fields[1]);

        (new CastlingRule())->validate($fields[2]);

        if ('-' !== $fields[3]) {
            (new Square())->validate($fields[3]);
        }

        return $string;
    }

    public function toArray(string $string): array
    {
        $array = [];
        $filtered = $string;
        for ($i = Square::SIZE['files']; $i >= 1; $i--) {
            $filtered = str_replace($i, str_repeat('.', $i), $filtered);
        }
        foreach (explode('/', $filtered) as $key => $val) {
            $array[Square::SIZE['files'] - $key - 1] = str_split($val);
        }

        return $array;
    }
}
