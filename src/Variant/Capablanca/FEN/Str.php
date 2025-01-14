<?php

namespace Chess\Variant\Capablanca\FEN;

use Chess\Variant\Capablanca\FEN\PiecePlacement;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Capablanca\CastlingRule;
use Chess\Variant\Classical\FEN\Str as ClassicalFenStr;
use Chess\Variant\Classical\PGN\Color;

class Str extends ClassicalFenStr
{
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

    public function toArray(string $string): array
    {
        $array = [];
        $filtered = $string;
        for ($i = Square::SIZE['files']; $i >= 1; $i--) {
            $filtered = str_replace($i, str_repeat('.', $i), $filtered);
        }
        foreach (explode('/', $filtered) as $key => $val) {
            $array[Square::SIZE['files'] - $key - 3] = str_split($val);
        }

        return $array;
    }
}
