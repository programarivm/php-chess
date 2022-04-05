<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Composition;
use Chess\Evaluation\AttackEvaluation;
use Chess\PGN\Symbol;

class RelativePinEvaluation extends AbstractEvaluation
{
    const NAME = 'relative_pin';

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
        $attackEvalBoard = (new AttackEvaluation($this->board))->eval();
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Symbol::K && $piece->getId() !== Symbol::Q) {
                $oppColor = $piece->getOppColor();
                $composition = (new Composition($this->board))
                    ->deletePieceByPosition($piece->getSquare())
                    ->getBoard();
                $attackEvalComposition = (new AttackEvaluation($composition))->eval();
                $attackEvalDiff = $attackEvalComposition[$oppColor] - $attackEvalBoard[$oppColor];
                if ($attackEvalDiff > 0) {
                    $this->result[$oppColor] += round($attackEvalDiff, 2);
                }
            }
        }

        return $this->result;
    }
}
