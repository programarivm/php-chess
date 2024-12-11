<?php

namespace Chess\Function;

use Chess\Eval\AttackEval;
use Chess\Eval\CheckabilityEval;

class CompleteFunction extends FastFunction
{
    public function __construct()
    {
        $this->eval = [
            ...$this->eval,
            AttackEval::class,
            CheckabilityEval::class,
        ];
    }
}
