<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\PiecePlacement;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

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
        $ranks = array_reverse(explode('/', $string));
        for ($i = count($ranks) - 1; $i >= 0; $i--) {
            $row = [];
            preg_match_all('!\d+!', $ranks[$i], $digits, PREG_OFFSET_CAPTURE);
            preg_match_all('/[a-zA-Z]{1}/', $ranks[$i], $letters, PREG_OFFSET_CAPTURE);
            $all = [...$digits[0], ...$letters[0]];
            usort($all, function ($a, $b) {
                return $a[1] <=> $b[1];
            });
            foreach ($all as $item) {
                !is_numeric($item[0])
                    ? $elem = [" {$item[0]} "]
                    : $elem = array_fill(0, $item[0], ' . ');
                $row = [...$row, ...$elem];
            }
            $array[$i] = $row;
        }

        return $array;
    }
}
