<?php

namespace Chess\Tutor;

use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Human-like phrase.
 *
 * @license GPL
 */
class PiecePhrase
{
    /**
     * Array of phrases.
     *
     * @var array
     */
    public static $phrase = [
        [
            'id' => "K",
            'color' => "w",
            'meanings' => [
                "White's king",
                "white king",
            ],
        ],
        [
            'id' => "K",
            'color' => "b",
            'meanings' => [
                "Black's king",
                "black king",
            ],
        ],
        [
            'id' => "N",
            'color' => "w",
            'meanings' => [
                "White's knight",
                "white knight",
            ],
        ],
        [
            'id' => "N",
            'color' => "b",
            'meanings' => [
                "Black's knight",
                "black knight",
            ],
        ],
        [
            'id' => "P",
            'color' => "w",
            'meanings' => [
                "White's pawn",
                "white pawn",
            ],
        ],
        [
            'id' => "P",
            'color' => "b",
            'meanings' => [
                "Black's pawn",
                "black pawn",
            ],
        ],
        [
            'id' => "Q",
            'color' => "w",
            'meanings' => [
                "White's queen",
                "white queen",
            ],
        ],
        [
            'id' => "Q",
            'color' => "b",
            'meanings' => [
                "Black's queen",
                "black queen",
            ],
        ],
        [
            'id' => "R",
            'color' => "w",
            'meanings' => [
                "White's rook",
                "white rook",
            ],
        ],
        [
            'id' => "R",
            'color' => "b",
            'meanings' => [
                "Black's rook",
                "black rook",
            ],
        ],
    ];

    public static function deterministic(AbstractPiece $piece): ?string
    {
        foreach (self::$phrase as $item) {
            if (
                $item['id'] === $piece->getId() &&
                $item['color'] === $piece->getColor()
            ) {
                return "{$item['meanings'][0]} on {$piece->getSq()}";
            }
        }

        return null;
    }
}
