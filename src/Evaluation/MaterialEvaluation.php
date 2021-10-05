<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

/**
 * Material.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class MaterialEvaluation extends AbstractEvaluation
{
    const NAME = 'material';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate($feature = null): array
    {
        foreach ($this->board->getPiecesByColor(Symbol::WHITE) as $piece) {
            if ($piece->getIdentity() !== Symbol::KING) {
                $this->result[Symbol::WHITE] += $this->value[$piece->getIdentity()];
            }
        }
        foreach ($this->board->getPiecesByColor(Symbol::BLACK) as $piece) {
            if ($piece->getIdentity() !== Symbol::KING) {
                $this->result[Symbol::BLACK] += $this->value[$piece->getIdentity()];
            }
        }

        return $this->result;
    }
}
