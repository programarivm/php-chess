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

    /**
     * It's true if the metric is inversely correlated to the balance.
     * Example - directly correlated: the material is directly correlated to the advantage, if white has more
     * material than black than the balance tends towards white.
     * Example - inversely correlated: isolated pawns are inversely correlated to the advantage, if white has more
     * isolated pawns than black than the balance tends towards black.
     * @var
     */
    public static $isInverselyCorrelated = false;

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
    }
}
