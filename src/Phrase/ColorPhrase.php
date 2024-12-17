<?php

namespace Chess\Phrase;

class ColorPhrase
{
    public static $phrase = [
        [
            'color' => "w",
            'meaning' => "White",
        ],
        [
            'color' => "b",
            'meaning' => "Black",
        ],
    ];

    public static function sentence(string $color): ?string
    {
        foreach (self::$phrase as $item) {
            if ($item['color'] === $color) {
                return $item['meaning'];
            }
        }

        return null;
    }
}
