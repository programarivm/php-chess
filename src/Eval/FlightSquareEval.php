<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Flight Square Evaluation
 *
 * The safe squares to which the king can move if it is threatened.
 */
class FlightSquareEval extends AbstractEval
{
    use ExplainEvalTrait {
        explain as private doExplain;
    }

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Flight square';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject =  [
            "White's king",
            "Black's king",
        ];

        $this->observation = [
            "has more safe squares to move to than its counterpart",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::K) {
                foreach ($piece->mobility as $sq) {
                    if (!in_array($sq, $this->board->spaceEval[$piece->oppColor()]) &&
                        in_array($sq, $this->board->sqCount['free'])
                    ) {
                        $this->result[$piece->color] += 1;
                    }
                }
            }
        }
    }

    /**
     * Explain the evaluation.
     *
     * @return array
     */
    public function explain(): array
    {
        $this->doExplain($this->result);

        return $this->explanation;
    }
}
