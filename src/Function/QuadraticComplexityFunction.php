<?php

namespace Chess\Function;

use Chess\Eval\AttackEval;
use Chess\Eval\CheckmateInOneEval;
use Chess\Eval\CheckmateInPlyEval;

/**
 * QuadraticComplexityFunction
 *
 * Quadratic evaluation function.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class QuadraticComplexityFunction extends AbstractFunction
{
    const NAME = 'Quadratic';

    public function __construct()
    {
        $this->eval = (new LinearComplexityFunction())->getEval();

        $this->eval = [
            ...$this->eval,
            AttackEval::class,
            CheckmateInPlyEval::class,
            CheckmateInOneEval::class,
        ];
    }
}
