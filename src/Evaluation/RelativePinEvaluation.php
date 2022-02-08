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

    public function evaluate(): array
    {
        $attackEvaldBoard = (new AttackEvaluation($this->board))->evaluate();
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getIdentity() !== Symbol::KING && $piece->getIdentity() !== Symbol::QUEEN) {
                $oppColor = $piece->getOppColor();
                $composition = (new Composition($this->board))
                    ->deletePieceByPosition($piece->getPosition())
                    ->getBoard();
                $attackEvaldComposition = (new AttackEvaluation($composition))->evaluate();
                $attackEvaldDiff = $attackEvaldComposition[$oppColor] - $attackEvaldBoard[$oppColor];
                if ($attackEvaldDiff > 0) {
                    $this->result[$oppColor] += round($attackEvaldDiff, 2);
                }
            }
        }

        return $this->result;
    }
}
