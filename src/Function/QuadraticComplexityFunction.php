<?php

namespace Chess\Function;

use Chess\Eval\AttackEval;

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
        $this->eval = [
            ...(new LinearComplexityFunction())->getEval(),
            AttackEval::class,
        ];
    }
}
