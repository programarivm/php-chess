<?php

namespace Chess\Function;

use Chess\Eval\BishopPairEval;
use Chess\Eval\IsolatedPawnEval;
use Chess\Eval\PressureEval;
use Chess\Eval\ProtectionEval;
use Chess\Eval\SqOutpostEval;

abstract class AbstractFunction
{
    public array $dependsOn = [
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

    public function dependencies($board): array
    {
        foreach ($this->dependsOn as $key => $val) {
            $dependencies[$key] = new $val($board);
        }

        return $dependencies;
    }
}
