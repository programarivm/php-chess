<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

class SquareOutpostEvaluation extends AbstractEvaluation
{
    const NAME = 'square_outpost';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => [],
            Symbol::BLACK => [],
        ];
    }

    public function evaluate(): array
    {
        // TODO

        return $this->result;
    }
}
