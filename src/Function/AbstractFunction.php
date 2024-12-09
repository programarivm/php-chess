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
        BishopPairEval::NAME => BishopPairEval::class,
        IsolatedPawnEval::NAME => IsolatedPawnEval::class,
        PressureEval::NAME => PressureEval::class,
        ProtectionEval::NAME => ProtectionEval::class,
        SqOutpostEval::NAME => SqOutpostEval::class,
    ];

    public function getEval(): array
    {
        return $this->eval;
    }

    public function names(): array
    {
        foreach ($this->eval as $key => $val) {
            $names[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $names;
    }
}
