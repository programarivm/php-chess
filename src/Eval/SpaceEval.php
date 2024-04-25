<?php

namespace Chess\Eval;

use Chess\Eval\SqCount;
use Chess\Tutor\SpaceEvalSentence;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * Space evaluation.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class SpaceEval extends AbstractEval implements DiscreteEvalInterface
{
    const NAME = 'Space';

    private object $sqCount;

    protected array $phrase = [
        Color::W => [
            [
                'diff' => 8,
                'meaning' => "White has a total space advantage.",
            ],
            [
                'diff' => 5,
                'meaning' => "White has a significant space advantage.",
            ],
            [
                'diff' => 3,
                'meaning' => "White has a kind of space advantage.",
            ],
            [
                'diff' => 2,
                'meaning' => "White has a tiny space advantage.",
            ],
        ],
        Color::B => [
            [
                'diff' => -8,
                'meaning' => "Black has a total space advantage.",
            ],
            [
                'diff' => -5,
                'meaning' => "Black has a significant space advantage.",
            ],
            [
                'diff' => -3,
                'meaning' => "Black has a kind of space advantage.",
            ],
            [
                'diff' => -2,
                'meaning' => "Black has a tiny space advantage.",
            ],
        ],
    ];

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->sqCount = (new SqCount($board))->count();

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($pieces = $this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::K) {
                $this->result[$piece->getColor()] = array_unique(
                    [
                        ...$this->result[$piece->getColor()],
                        ...array_intersect(
                            (array) $piece->getMobility(),
                            $this->sqCount->free
                        )
                    ]
                );
            } elseif ($piece->getId() === Piece::P) {
                $this->result[$piece->getColor()] = array_unique(
                    [
                        ...$this->result[$piece->getColor()],
                        ...array_intersect(
                            $piece->getCaptureSqs(),
                            $this->sqCount->free
                        )
                    ]
                );
            } else {
                $this->result[$piece->getColor()] = array_unique(
                    [
                        ...$this->result[$piece->getColor()],
                        ...array_diff(
                            $piece->sqs(),
                            $this->sqCount->used->{$piece->oppColor()}
                        )
                    ]
                );
            }
        }

        $this->explain($this->result);
    }

    private function explain(array $result): void
    {
        $result[Color::W] = count($result[Color::W]);
        $result[Color::B] = count($result[Color::B]);

        if ($sentence = $this->sentence($result)) {
            $this->phrases[] = $sentence;
        }
    }
}
