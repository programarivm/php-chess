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

    public function evaluate(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            switch ($piece->getId()) {
                case Symbol::KING:
                    // TODO ...
                    break;
                case Symbol::PAWN:
                    foreach ($piece->getCaptureSquares() as $sq) {
                        if ($item = $this->board->getPieceBySq($sq)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $id = $item->getId();
                                if ($id !== Symbol::KING && $this->value[Symbol::PAWN] < $this->value[$id]) {
                                    $this->result[$piece->getColor()] += $this->value[$id] - $this->value[Symbol::PAWN];
                                }
                            }
                        }
                    }
                    break;
                default:
                    foreach ($piece->getSquares() as $sq) {
                        if ($item = $this->board->getPieceBySq($sq)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $id = $item->getId();
                                if ($id !== Symbol::KING && $this->value[$piece->getId()] < $this->value[$id]) {
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
