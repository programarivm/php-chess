<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

class RelativeForkEvaluation extends AbstractForkEvaluation
{
    const NAME = 'relative_fork';

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
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Symbol::KING) {
                $attackedPieces = $this->attackedPieces($piece);
                if (count($attackedPieces) >= 2 && !$this->isKingAttacked($attackedPieces)) {
                    $this->result[$piece->getColor()] = $this->sumValues($piece, $attackedPieces);
                }
            }
        }

        return $this->result;
    }
}
