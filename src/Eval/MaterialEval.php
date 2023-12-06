<?php

namespace Chess\Eval;

use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Material.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class MaterialEval extends AbstractEval
{
    const NAME = 'Material';

    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "White has a decisive material advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "White has a significant material advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "White has some material advantage.",
            ],
            [
                'diff' => 1,
                'meaning' => "White has a tiny material advantage.",
            ],
        ],
        Color::B => [
            [
                'diff' => 4,
                'meaning' => "Black has a decisive material advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "Black has a significant material advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "Black has some material advantage.",
            ],
            [
                'diff' => 1,
                'meaning' => "Black has a tiny material advantage.",
            ],
        ],
    ];

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces(Color::W) as $piece) {
            if ($piece->getId() !== Piece::K) {
                $this->result[Color::W] += self::$value[$piece->getId()];
            }
        }

        foreach ($this->board->getPieces(Color::B) as $piece) {
            if ($piece->getId() !== Piece::K) {
                $this->result[Color::B] += self::$value[$piece->getId()];
            }
        }

        $this->result[Color::W] = round($this->result[Color::W], 2);
        $this->result[Color::B] = round($this->result[Color::B], 2);

        $this->explain($this->result);
    }

    private function explain(array $result): void
    {
        if ($sentence = $this->sentence($result)) {
            $this->phrases[] = $sentence;
        }
    }
}
