<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

class KnightOutpostEvaluation extends AbstractOutpostEvaluation
{
    const NAME = 'knigt_outpost';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate(): array
    {
        // TODO

        return $this->result;
    }
}
