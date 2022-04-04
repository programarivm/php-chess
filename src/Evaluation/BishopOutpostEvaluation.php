<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SquareOutpostEvaluation;
use Chess\PGN\Symbol;

class BishopOutpostEvaluation extends AbstractEvaluation
{
    const NAME = 'bishop_outpost';

    private $sqOutpostEvald;

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->sqOutpostEvald = (new SquareOutpostEvaluation($board))->evaluate();

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate(): array
    {
        foreach ($this->sqOutpostEvald as $key => $val) {
            foreach ($val as $sq) {
                if ($piece = $this->board->getPieceBySq($sq)) {
                    if ($piece->getColor() === $key && $piece->getId() === Symbol::BISHOP) {
                        $this->result[$key] += 1;
                    }
                }
            }
        }

        return $this->result;
    }
}
