<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SpaceEvaluation;
use Chess\PGN\Symbol;

/**
 * Check.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class CheckEvaluation extends AbstractEvaluation
{
    const NAME = 'check';

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
        $this->result = [
            Symbol::WHITE => (int) ($this->board->getTurn() === Symbol::BLACK && $this->board->isCheck()),
            Symbol::BLACK => (int) ($this->board->getTurn() === Symbol::WHITE && $this->board->isCheck()),
        ];

        return $this->result;
    }
}
