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

    public function eval(): array
    {
        foreach ($this->board->getPiecesByColor(Symbol::WHITE) as $piece) {
            if ($piece->getId() !== Symbol::K) {
                $this->result[Symbol::WHITE] += $this->value[$piece->getId()];
            }
        }
        foreach ($this->board->getPiecesByColor(Symbol::BLACK) as $piece) {
            if ($piece->getId() !== Symbol::K) {
                $this->result[Symbol::BLACK] += $this->value[$piece->getId()];
            }
        }

        return $this->result;
    }
}
