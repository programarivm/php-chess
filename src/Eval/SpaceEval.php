<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Space Evaluation
 *
 * This is a measure of the number of squares controlled by each player.
 */
class SpaceEval extends AbstractEval
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
                    ...$piece->flow,
                ];
            } elseif ($piece->id === Piece::P) {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...$piece->xSqs,
                ];
            } else {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...$piece->moveSqs(),
                ];
            }
        }

        $this->result[Color::W] = array_keys(array_flip($this->result[Color::W]));
        $this->result[Color::B] = array_keys(array_flip($this->result[Color::B]));

        $this->result[Color::W] = array_intersect($this->result[Color::W], $this->board->sqCount['free']);
        $this->result[Color::B] = array_intersect($this->result[Color::B], $this->board->sqCount['free']);
    }
}
