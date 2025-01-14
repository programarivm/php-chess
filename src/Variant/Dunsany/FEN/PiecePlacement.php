<?php

namespace Chess\Variant\Dunsany\FEN;

use Chess\Variant\Classical\FEN\PiecePlacement as ClassicalPiecePlacement;
use Chess\Variant\Classical\PGN\Color;

class PiecePlacement extends ClassicalPiecePlacement
{
    protected function kings(array $fields)
    {
        $result = [
            Color::W => 0,
            Color::B => 0,
        ];

        foreach ($fields as $field) {
            $count = count_chars($field, 1);
            foreach ($count as $key => $val) {
                if (chr($key) === 'K') {
                    $result[Color::W] += $val;
                } elseif (chr($key) === 'k') {
                    $result[Color::B] += $val;
                }
            }
        }

        return $result[Color::W] === 0 && $result[Color::B] === 1;
    }
}
