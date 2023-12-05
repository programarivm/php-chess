<?php

namespace Chess\Tutor;

use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Human-like sentence.
 *
 * @license GPL
 */
class CenterEvalSentence
{
    /**
     * Array of phrases.
     *
     * @var array
     */
    public static $phrase = [
        [
            'diff' => -4,
            'color' => 'b',
            'meanings' => [
                "Black has an absolute control of the center.",
            ],
        ],
        [
            'diff' => -3,
            'color' => 'b',
            'meanings' => [
                "Black has a remarkable control of the center.",
            ],
        ],
        [
            'diff' => -2,
            'color' => 'b',
            'meanings' => [
                "Black has a somewhat better control of the center.",
            ],
        ],
        [
            'diff' => -1,
            'color' => 'b',
            'meanings' => [
                "Black has a slightly better control of the center.",
            ],
        ],
        [
            'diff' => 4,
            'color' => 'w',
            'meanings' => [
                "White has an absolute control of the center.",
            ],
        ],
        [
            'diff' => 3,
            'color' => 'w',
            'meanings' => [
                "White has a remarkable control of the center.",
            ],
        ],
        [
            'diff' => 2,
            'color' => 'w',
            'meanings' => [
                "White has a somewhat better control of the center.",
            ],
        ],
        [
            'diff' => 1,
            'color' => 'w',
            'meanings' => [
                "White has a slightly better control of the center.",
            ],
        ],
    ];

    public static function predictable(array $result): ?string
    {
        $diff = $result[Color::W] - $result[Color::B];

        foreach (self::$phrase as $item) {
            if ($item['color'] === Color::W) {
                if ($diff > $item['diff']) {
                    return $item['meanings'][0];
                }
            } elseif ($item['color'] === Color::B) {
                if ($diff < $item['diff']) {
                    return $item['meanings'][0];
                }
            }
        }

        return null;
    }
}
