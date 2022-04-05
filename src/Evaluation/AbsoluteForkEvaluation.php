<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

class AbsoluteForkEvaluation extends AbstractForkEvaluation
{
    const NAME = 'absolute_fork';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function eval(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Symbol::K) {
                $attackedPieces = $this->attackedPieces($piece);
                if ($this->isKingAttacked($attackedPieces)) {
                    $this->result[$piece->getColor()] = $this->sumValues($piece, $attackedPieces);
                }
            }
        }

        return $this->result;
    }
}
