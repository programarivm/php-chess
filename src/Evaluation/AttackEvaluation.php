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
            switch ($piece->getIdentity()) {
                case Symbol::KING:
                    // TODO ...
                    break;
                case Symbol::PAWN:
                    foreach ($piece->getCaptureSquares() as $square) {
                        if ($item = $this->board->getPieceByPosition($square)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $identity = $item->getIdentity();
                                if ($identity !== Symbol::KING && $this->value[Symbol::PAWN] < $this->value[$identity]) {
                                    $this->result[$piece->getColor()] += $this->value[$identity] - $this->value[Symbol::PAWN];
                                }
                            }
                        }
                    }
                    break;
                default:
                    foreach ($piece->getLegalMoves() as $square) {
                        if ($item = $this->board->getPieceByPosition($square)) {
                            if ($item->getColor() !== $piece->getColor()) {
                                $identity = $item->getIdentity();
                                if ($identity !== Symbol::KING && $this->value[$piece->getIdentity()] < $this->value[$identity]) {
                                    $this->result[$piece->getColor()] += $this->value[$identity] - $this->value[$piece->getIdentity()];
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
