<?php

namespace Chess\Eval;

use Chess\Eval\SpaceEval;
use Chess\Eval\SqCount;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Flight Square Evaluation
 *
 * The safe squares to which the king can move if it is threatened.
 */
class FlightSquareEval extends AbstractEval implements
    ExplainEvalInterface
{
    use ExplainEvalTrait;

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
            "has more safe squares to move to than its counterpart, if threatened",
        ];

        $spEval = (new SpaceEval($this->board))->getResult();
        $sqCount = (new SqCount($this->board))->count();

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::K) {
                foreach ($piece->mobility as $sq) {
                    if (!in_array($sq, $spEval[$piece->oppColor()]) &&
                        in_array($sq, $sqCount['free'])
                    ) {
                        $this->result[$piece->color] += 1;
                    }
                }
            }
        }

        $this->explain($this->result);
    }
}
