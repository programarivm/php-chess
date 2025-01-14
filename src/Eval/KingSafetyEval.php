<?php

namespace Chess\Eval;

use Chess\Eval\PressureEval;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

/**
 * King Safety Evaluation
 *
 * An unsafe king leads to uncertainty. The probability of unexpected, forced
 * moves will increase as the opponent's pieces get closer to it.
 *
 * @see \Chess\Eval\AbstractEval
 */
class KingSafetyEval extends AbstractEval implements InverseEvalInterface
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'King safety';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 4];

        $this->subject =  [
            'The black pieces',
            'The white pieces',
        ];

        $this->observation = [
            "are timidly approaching the other side's king",
            "are approaching the other side's king",
            "are getting worryingly close to the adversary's king",
            "are more than desperately close to the adversary's king",
        ];

        $pressEval = (new PressureEval($this->board))->result;

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::K) {
                foreach ($piece->flow as $sq) {
                    if ($pieceBySq = $this->board->pieceBySq($sq)) {
                        if ($pieceBySq->color === $piece->oppColor()) {
                            $this->result[$piece->color] += 1;
                        }
                    }
                    if (in_array($sq, $pressEval[$piece->oppColor()])) {
                        $this->result[$piece->color] += 1;
                    }
                    if (in_array($sq, $this->board->spaceEval[$piece->oppColor()])) {
                        $this->result[$piece->color] += 1;
                    }
                }
            }
        }
    }
}
