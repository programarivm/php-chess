<?php

namespace Chess\Tutor;

use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Human-like sentence.
 *
 * @license GPL
 */
class SpaceEvalSentence
{
    /**
     * Array of phrases.
     *
     * @var array
     */
    public static $phrase = [
        Color::W => [
            [
                'diff' => 8,
                'meanings' => [
                    "White has an absolute space advantage.",
                ],
            ],
            [
                'diff' => 5,
                'meanings' => [
                    "White has a remarkable space advantage.",
                ],
            ],
            [
                'diff' => 3,
                'meanings' => [
                    "White has a somewhat better space advantage.",
                ],
            ],
            [
                'diff' => 2,
                'meanings' => [
                    "White has a slightly better space advantage.",
                ],
            ],
        ],
        Color::B => [
            [
                'diff' => -8,
                'meanings' => [
                    "Black has an absolute space advantage.",
                ],
            ],
            [
                'diff' => -5,
                'meanings' => [
                    "Black has a remarkable space advantage.",
                ],
            ],
            [
                'diff' => -3,
                'meanings' => [
                    "Black has a somewhat better space advantage.",
                ],
            ],
            [
                'diff' => -2,
                'meanings' => [
                    "Black has a slightly better space advantage.",
                ],
            ],
        ],
    ];

    public static function predictable(array $result): ?string
    {
        $diff = count($result[Color::W]) - count($result[Color::B]);

        if ($diff > 0) {
            foreach (self::$phrase[Color::W] as $item) {
                if ($diff > $item['diff']) {
                    return $item['meanings'][0];
                }
            }
        } else {
            foreach (self::$phrase[Color::B] as $item) {
                if ($diff < $item['diff']) {
                    return $item['meanings'][0];
                }
            }
        }

        return null;
    }
}
