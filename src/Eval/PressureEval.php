<?php

namespace Chess\Eval;

use Chess\Eval\SqCount;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * Pressure evaluation.
 *
 * Squares being threatened.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class PressureEval extends AbstractEval implements DiscreteEvalInterface
{
    const NAME = 'Pressure';

    /**
     * Count squares.
     *
     * @var object
     */
    private object $sqCount;

    /**
     * Human-like phrases.
     *
     * @var array
     */
    protected array $phrase = [
        Color::W => [
            [
                'diff' => 4,
                'meaning' => "The white player is utterly pressuring more squares than its opponent.",
            ],
            [
                'diff' => 3,
                'meaning' => "The white player is really pressuring more squares than its opponent.",
            ],
            [
                'diff' => 2,
                'meaning' => "The white player is somewhat pressuring more squares than its opponent.",
            ],
            [
                'diff' => 1,
                'meaning' => "The white player is pressuring a little bit more squares than its opponent.",
            ],
        ],
        Color::B => [
            [
                'diff' => -4,
                'meaning' => "The black player is utterly pressuring more squares than its opponent.",
            ],
            [
                'diff' => -3,
                'meaning' => "The black player is really pressuring more squares than its opponent.",
            ],
            [
                'diff' => -2,
                'meaning' => "The black player is somewhat pressuring more squares than its opponent.",
            ],
            [
                'diff' => -1,
                'meaning' => "The black player is pressuring a little bit more squares than its opponent.",
            ],
        ],
    ];

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
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
                $this->result[$piece->getColor()] = [
                    ...$this->result[$piece->getColor()],
                    ...array_intersect(
                        (array) $piece->getMobility(),
                        $this->sqCount->used->{$piece->oppColor()}
                    )
                ];
            } elseif ($piece->getId() === Piece::P) {
                $this->result[$piece->getColor()] = [
                    ...$this->result[$piece->getColor()],
                    ...array_intersect(
                        $piece->getCaptureSqs(),
                        $this->sqCount->used->{$piece->oppColor()}
                    )
                ];
            } else {
                $this->result[$piece->getColor()] = [
                    ...$this->result[$piece->getColor()],
                    ...array_intersect(
                        $piece->sqs(),
                        $this->sqCount->used->{$piece->oppColor()}
                    )
                ];
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
