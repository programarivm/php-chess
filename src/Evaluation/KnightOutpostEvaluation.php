<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SquareOutpostEvaluation;
use Chess\PGN\Symbol;

class KnightOutpostEvaluation extends AbstractEvaluation
{
    const NAME = 'knigt_outpost';

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
            foreach ($val as $square) {
                if ($piece = $this->board->getPieceByPosition($square)) {
                    if ($piece->getColor() === $key && $piece->getIdentity() === Symbol::KNIGHT) {
                        $this->result[$key] += 1;
                    }
                }
            }
        }

        return $this->result;
    }
}
