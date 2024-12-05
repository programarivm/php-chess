<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Space Evaluation
 *
 * This is a measure of the number of squares controlled by each player.
 */
class SpaceEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Space';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->range = [1, 9];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight space advantage",
            "has a moderate space advantage",
            "has a total space advantage",
        ];

        foreach ($pieces = $this->board->pieces() as $piece) {
            if ($piece->id === Piece::K) {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->mobility,
                        $this->board->sqCount['free']
                    )
                ];
            } elseif ($piece->id === Piece::P) {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->captureSqs,
                        $this->board->sqCount['free']
                    )
                ];
            } else {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_diff(
                        $piece->moveSqs(),
                        $this->board->sqCount['used'][$piece->oppColor()]
                    )
                ];
            }
        }

        $this->result[Color::W] = array_flip(array_flip($this->result[Color::W]));
        $this->result[Color::B] = array_flip(array_flip($this->result[Color::B]));

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);
    }
}
