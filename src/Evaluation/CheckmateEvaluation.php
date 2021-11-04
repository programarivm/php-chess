<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;

/**
 * Checkmate.
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

    public function evaluate($feature = null): array
    {
        foreach ($this->board->getPossibleMoves() as $level1Move) {
            $this->board->play(Convert::toStdObj($this->board->getTurn(), $level1Move));
            if ($this->board->isMate()) {
                $this->result[$this->board->getTurn()] = 1;
                return $this->result;
            }
            foreach ($this->board->getPossibleMoves() as $level2Move) {
                $this->board->play(Convert::toStdObj(Symbol::oppColor($this->board->getTurn()), $level2Move));
                if ($this->board->isMate()) {
                    $this->result[Symbol::oppColor($this->board->getTurn())] = 1;
                    return $this->result;
                }
                foreach ($this->board->getPossibleMoves() as $level3Move) {
                    $this->board->play(Convert::toStdObj($this->board->getTurn(), $level3Move));
                    if ($this->board->isMate()) {
                        $this->result[$this->board->getTurn()] = 1;
                        return $this->result;
                    }
                    $this->board->undoMove($this->board->getCastling());
                }
                $this->board->undoMove($this->board->getCastling());
            }
            $this->board->undoMove($this->board->getCastling());
        }

        return $this->result;
    }
}
