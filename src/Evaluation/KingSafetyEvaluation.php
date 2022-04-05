<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\PressureEvaluation;
use Chess\Evaluation\SpaceEvaluation;
use Chess\PGN\Symbol;

/**
 * King safety.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class KingSafetyEvaluation extends AbstractEvaluation
{
    const NAME = 'safety';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 1,
            Symbol::BLACK => 1,
        ];
    }

    public function eval(): array
    {
        $pressEval = (new PressureEvaluation($this->board))->eval();
        $spEval = (new SpaceEvaluation($this->board))->eval();

        $this->color(Symbol::WHITE, $pressEval, $spEval);
        $this->color(Symbol::BLACK, $pressEval, $spEval);

        return $this->result;
    }

    private function color(string $color, array $pressEval, array $spEval)
    {
        $king = $this->board->getPiece($color, Symbol::K);
        foreach ($king->getTravel() as $key => $sq) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                if ($piece->getColor() === $king->getOppColor()) {
                    $this->result[$color] -= 1;
                }
            }
            if (in_array($sq, $pressEval[$king->getOppColor()])) {
                $this->result[$color] -= 1;
            }
            if (in_array($sq, $spEval[$king->getOppColor()])) {
                $this->result[$color] -= 1;
            }
        }
    }
}
