<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SqOutpostEvaluation;
use Chess\PGN\Symbol;

class KnightOutpostEvaluation extends AbstractEvaluation
{
    const NAME = 'knight_outpost';

    private $sqOutpostEval;

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->sqOutpostEval = (new SqOutpostEvaluation($board))->eval();

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function eval(): array
    {
        foreach ($this->sqOutpostEval as $key => $val) {
            foreach ($val as $sq) {
                if ($piece = $this->board->getPieceBySq($sq)) {
                    if ($piece->getColor() === $key && $piece->getId() === Symbol::N) {
                        $this->result[$key] += 1;
                    }
                }
            }
        }

        return $this->result;
    }
}
