<?php

namespace Chess\Evaluation;

use Chess\Composition;
use Chess\Board;
use Chess\PGN\Symbol;

class AbsolutePinEvaluation extends AbstractEvaluation implements InverseEvaluationInterface
{
    const NAME = 'absolute_pin';

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
            if ($piece->getId() !== Symbol::K) {
                $composition = (new Composition($this->board))
                    ->setTurn($piece->getOppColor())
                    ->deletePieceByPosition($piece->getSquare())
                    ->getBoard();
                if ($composition->isCheck()) {
                    $this->result[$piece->getColor()] += $this->value[$piece->getId()];
                }
            }
        }

        return $this->result;
    }
}
