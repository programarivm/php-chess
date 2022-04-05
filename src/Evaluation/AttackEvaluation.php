<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

/**
 * Attack evaluation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class AttackEvaluation extends AbstractEvaluation
{
    const NAME = 'attack';

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
            switch ($piece->getId()) {
                case Symbol::K:
                    // TODO ...
                    break;
                case Symbol::P:
                    foreach ($piece->getCaptureSquares() as $sq) {
                        if ($item = $this->board->getPieceBySq($sq)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $id = $item->getId();
                                if ($id !== Symbol::K && $this->value[Symbol::P] < $this->value[$id]) {
                                    $this->result[$piece->getColor()] += $this->value[$id] - $this->value[Symbol::P];
                                }
                            }
                        }
                    }
                    break;
                default:
                    foreach ($piece->getSqs() as $sq) {
                        if ($item = $this->board->getPieceBySq($sq)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $id = $item->getId();
                                if ($id !== Symbol::K && $this->value[$piece->getId()] < $this->value[$id]) {
                                    $this->result[$piece->getColor()] += $this->value[$id] - $this->value[$piece->getId()];
                                }
                            }
                        }
                    }
                    break;
            }
        }

        return $this->result;
    }
}
