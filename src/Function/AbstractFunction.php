<?php

namespace Chess\Function;

use Chess\Eval\BishopPairEval;
use Chess\Eval\IsolatedPawnEval;
use Chess\Eval\PressureEval;
use Chess\Eval\ProtectionEval;
use Chess\Eval\SqOutpostEval;

abstract class AbstractFunction
{
    public array $dependencies = [
        BishopPairEval::class,
        IsolatedPawnEval::class,
        PressureEval::class,
        ProtectionEval::class,
        SqOutpostEval::class,
    ];

    public function names(): array
    {
        foreach ($this->eval as $key => $val) {
            $names[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $names;
    }
}
