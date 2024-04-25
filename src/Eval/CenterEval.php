<?php

namespace Chess\Eval;

use Chess\Eval\SpaceEval;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Center.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class CenterEval extends AbstractEval implements DiscreteEvalInterface
{
    const NAME = 'Center';

    private array $center = [
        'a8' => 0, 'b8' => 0, 'c8' => 0, 'd8' => 0, 'e8' => 0, 'f8' => 0, 'g8' => 0, 'h8' => 0,
        'a7' => 0, 'b7' => 1, 'c7' => 1, 'd7' => 1, 'e7' => 1, 'f7' => 1, 'g7' => 1, 'h7' => 0,
        'a6' => 0, 'b6' => 1, 'c6' => 2, 'd6' => 2, 'e6' => 2, 'f6' => 2, 'g6' => 1, 'h6' => 0,
        'a5' => 0, 'b5' => 1, 'c5' => 2, 'd5' => 3, 'e5' => 3, 'f5' => 2, 'g5' => 1, 'h5' => 0,
        'a4' => 0, 'b4' => 1, 'c4' => 2, 'd4' => 3, 'e4' => 3, 'f4' => 2, 'g4' => 1, 'h4' => 0,
        'a3' => 0, 'b3' => 1, 'c3' => 2, 'd3' => 2, 'e3' => 2, 'f3' => 2, 'g3' => 1, 'h3' => 0,
        'a2' => 0, 'b2' => 1, 'c2' => 1, 'd2' => 1, 'e2' => 1, 'f2' => 1, 'g2' => 1, 'h2' => 0,
        'a1' => 0, 'b1' => 0, 'c1' => 0, 'd1' => 0, 'e1' => 0, 'f1' => 0, 'g1' => 0, 'h1' => 0,
    ];

    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "White is just controlling the center.",
            ],
            [
                'diff' => 3,
                'meaning' => "White has a significant control of the center.",
            ],
            [
                'diff' => 2,
                'meaning' => "White has a somewhat better control of the center.",
            ],
            [
                'diff' => 1,
                'meaning' => "White has a slightly better control of the center.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "Black is just controlling the center.",
            ],
            [
                'diff' => -3,
                'meaning' => "Black has a significant control of the center.",
            ],
            [
                'diff' => -2,
                'meaning' => "Black has a somewhat better control of the center.",
            ],
            [
                'diff' => -1,
                'meaning' => "Black has a slightly better control of the center.",
            ],
        ],
    ];

    public function __construct(Board $board)
    {
        $this->board = $board;

        $spEval = (new SpaceEval($this->board))->getResult();

        foreach ($this->center as $sq => $val) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                $this->result[$piece->getColor()] += self::$value[$piece->getId()] * $val;
            }
            if (in_array($sq, $spEval[Color::W])) {
                $this->result[Color::W] += $val;
            }
            if (in_array($sq, $spEval[Color::B])) {
                $this->result[Color::B] += $val;
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
