<?php

namespace Chess\Tutor;

use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Human-like sentence.
 *
 * @license GPL
 */
class DoubledPawnEvalSentence
{
    /**
     * Array of phrases.
     *
     * @var array
     */
    public static $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meanings' => [
                    "The white pieces are absolutely better in terms of doubled pawns.",
                ],
            ],
            [
                'diff' => 3,
                'meanings' => [
                    "The white pieces are remarkably better in terms of doubled pawns.",
                ],
            ],
            [
                'diff' => 2,
                'meanings' => [
                    "The white pieces are somewhat better in terms of doubled pawns.",
                ],
            ],
            [
                'diff' => 1,
                'meanings' => [
                    "The white pieces are slightly better in terms of doubled pawns.",
                ],
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meanings' => [
                    "The black pieces are absolutely better in terms of doubled pawns.",
                ],
            ],
            [
                'diff' => -3,
                'meanings' => [
                    "The black pieces are remarkably better in terms of doubled pawns.",
                ],
            ],
            [
                'diff' => -2,
                'meanings' => [
                    "The black pieces are somewhat better in terms of doubled pawns.",
                ],
            ],
            [
                'diff' => -1,
                'meanings' => [
                    "The black pieces are slightly better in terms of doubled pawns.",
                ],
            ],
        ],
    ];

    public static function predictable(array $result): ?string
    {
        $diff = $result[Color::W] - $result[Color::B];

        if ($diff > 0) {
            foreach (self::$phrase[Color::W] as $item) {
                if ($diff >= $item['diff']) {
                    return $item['meanings'][0];
                }
            }
        } elseif ($diff < 0) {
            foreach (self::$phrase[Color::B] as $item) {
                if ($diff <= $item['diff']) {
                    return $item['meanings'][0];
                }
            }
        }

        return null;
    }
}
