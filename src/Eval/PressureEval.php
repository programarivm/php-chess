<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Pressure Evaluation
 *
 * This is a measure of the number of squares targeted by each player that
 * require special attention. It often indicates the step prior to an attack.
 * The player with the greater number of them has an advantage.
 *
 * @see \Chess\Eval\AttackEval
 */
class PressureEval extends AbstractEval
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Pressure';

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

        $this->range = [1];

        $this->subject = [
            'The white player',
            'The black player',
        ];

        $this->observation = [
            "is pressuring more squares than its opponent",
        ];

        foreach ($pieces = $this->board->pieces() as $piece) {
            if ($piece->id === Piece::K) {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->flow,
                        $this->board->sqCount['used'][$piece->oppColor()]
                    )
                ];
            } elseif ($piece->id === Piece::P) {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->xSqs,
                        $this->board->sqCount['used'][$piece->oppColor()]
                    )
                ];
            } else {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->moveSqs(),
                        $this->board->sqCount['used'][$piece->oppColor()]
                    )
                ];
            }
        }
    }
}
