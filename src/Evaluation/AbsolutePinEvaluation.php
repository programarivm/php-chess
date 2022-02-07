<?php

namespace Chess\Evaluation;

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

    public function evaluate(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            $clone = (unserialize(serialize($this->board)))->setTurn($piece->getOppColor());
            switch ($piece->getIdentity()) {
                case Symbol::KING:
                    break;
                default:
                    $clone->deletePieceByPosition($piece->getPosition());
                    if ($clone->isCheck()) {
                        $this->result[$piece->getColor()] += $this->value[$piece->getIdentity()];
                    }
                break;
            }
        }

        return $this->result;
    }
}
