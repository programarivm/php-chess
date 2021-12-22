<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

/**
 * Abstract evaluation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractEvaluation
{
    protected $isInversed;

    protected $board;

    protected $value;

    protected $result;

    public function __construct(Board $board)
    {
        $this->isInversed = false;

        $this->board = $board;

        $this->value = [
            Symbol::PAWN => 1,
            Symbol::KNIGHT => 3.2,
            Symbol::BISHOP => 3.33,
            Symbol::ROOK => 5.1,
            Symbol::QUEEN => 8.8,
        ];
    }

    public function isInversed()
    {
        return $this->isInversed;
    }
}
