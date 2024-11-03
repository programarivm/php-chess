<?php

namespace Chess\Eval;

use Chess\Eval\SqCount;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Pressure Evaluation
 *
 * This is a measure of the number of squares targeted by each player that
 * require special attention. It often indicates the step prior to an attack.
 * The player with the greater number of them has an advantage.
 *
 * @see \Chess\Eval\AttackEval
 */
class PressureEval extends AbstractEval implements ExplainEvalInterface
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

        $this->range = [1, 4];

        $this->subject = [
            'The white player',
            'The black player',
        ];

        $this->observation = [
            "is pressuring a little bit more squares than its opponent",
            "is significantly pressuring more squares than its opponent",
            "is utterly pressuring more squares than its opponent",
        ];

        $sqCount = (new SqCount($board))->count();

        foreach ($pieces = $this->board->pieces() as $piece) {
            if ($piece->id === Piece::K) {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->mobility,
                        $sqCount['used'][$piece->oppColor()]
                    )
                ];
            } elseif ($piece->id === Piece::P) {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->captureSqs,
                        $sqCount['used'][$piece->oppColor()]
                    )
                ];
            } else {
                $this->result[$piece->color] = [
                    ...$this->result[$piece->color],
                    ...array_intersect(
                        $piece->moveSqs(),
                        $sqCount['used'][$piece->oppColor()]
                    )
                ];
            }
        }

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);
    }
}
