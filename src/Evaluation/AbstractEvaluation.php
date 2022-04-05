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

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->value = [
            Symbol::P => 1,
            Symbol::N => 3.2,
            Symbol::B => 3.33,
            Symbol::K => 4,
            Symbol::R => 5.1,
            Symbol::Q => 8.8,
        ];
    }
}
