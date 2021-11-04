<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;

/**
 * Checkmate in one.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class CheckmateEvaluation extends AbstractEvaluation
{
    const NAME = 'checkmate';

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
        foreach ($this->board->getPossibleMoves() as $move) {
            $this->board->play(Convert::toStdObj($this->board->getTurn(), $move));
            if ($this->board->isMate()) {
                $this->result[$this->board->getTurn()] = 1;
                return $this->result;
            } else {
                $this->board->undoMove($this->board->getCastling());
            }
        }

        return $this->result;
    }
}
