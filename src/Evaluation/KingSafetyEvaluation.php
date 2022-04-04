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

    public function evaluate(): array
    {
        $pressEvald = (new PressureEvaluation($this->board))->evaluate();
        $spEvald = (new SpaceEvaluation($this->board))->evaluate();

        $this->color(Symbol::WHITE, $pressEvald, $spEvald);
        $this->color(Symbol::BLACK, $pressEvald, $spEvald);

        return $this->result;
    }

    private function color(string $color, array $pressEvald, array $spEvald)
    {
        $king = $this->board->getPiece($color, Symbol::KING);
        foreach ($king->getScope() as $key => $sq) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                if ($piece->getColor() === $king->getOppColor()) {
                    $this->result[$color] -= 1;
                }
            }
            if (in_array($sq, $pressEvald[$king->getOppColor()])) {
                $this->result[$color] -= 1;
            }
            if (in_array($sq, $spEvald[$king->getOppColor()])) {
                $this->result[$color] -= 1;
            }
        }
    }
}
