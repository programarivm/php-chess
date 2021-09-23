<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

class DoubledPawnEvaluation extends AbstractEvaluation
{
    const NAME = 'doubled_pawn';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate($feature = null): array
    {
        // TODO

        return $this->result;
    }
}
