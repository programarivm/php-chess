<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;

/**
 * Checkability Evaluation
 *
 * Having a king that can be checked is usually considered a disadvantage,
 * and vice versa, it is considered advantageous to have a king that cannot
 * be checked. A checkable king is vulnerable to forcing moves.
 */
class CheckabilityEval extends AbstractEval
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Checkability';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            "Black's king",
            "White's king",
        ];

        $this->observation = [
            "can be checked so it is vulnerable to forced moves",
        ];

        foreach ($this->board->pieces($this->board->turn) as $piece) {
            foreach ($piece->moveSqs() as $sq) {
                $clone = $this->board->clone();
                if ($clone->playLan($this->board->turn, "{$piece->sq}$sq")) {
                    if ($clone->isCheck()) {
                        $this->result[$this->board->turn] = 1;
                        break 2;
                    }
                }
            }
        }
    }
}
