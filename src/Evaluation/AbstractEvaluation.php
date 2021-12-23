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
    protected $board;

    protected $value;

    protected $result;

    protected $isInverse;

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->value = [
            Symbol::PAWN => 1,
            Symbol::KNIGHT => 3.2,
            Symbol::BISHOP => 3.33,
            Symbol::ROOK => 5.1,
            Symbol::QUEEN => 8.8,
        ];

        $this->isInverse = false;
    }

    public function isInverse()
    {
        return $this->isInverse;
    }
}
