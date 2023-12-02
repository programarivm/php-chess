<?php

namespace Chess\Tutor;

use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Human-like phrase.
 *
 * @license GPL
 */
class ColorPhrase
{
    /**
     * Array of phrases.
     *
     * @var array
     */
    public static $phrase = [
        [
            'color' => "w",
            'meanings' => [
                "White",
            ],
        ],
        [
            'color' => "b",
            'meanings' => [
                "Black",
            ],
        ],
    ];

    public static function deterministic(string $color): ?string
    {
        foreach (self::$phrase as $item) {
            if ($item['color'] === $color) {
                return $item['meanings'][0];
            }
        }

        return null;
    }
}
