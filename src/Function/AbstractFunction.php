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

    public array $dependencies;

    public function names(): array
    {
        foreach ($this->eval as $key => $val) {
            $names[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $names;
    }

    public function dependencies($board)
    {
        $dependencies = [];
        foreach ($this->dependsOn as $key => $val) {
            $dependencies[$key] = new $val($board);
        }

        return $dependencies;
    }

    public function resolve($board, $dependencies, $class, $name)
    {
        if ($name) {
            return new $class($board, $dependencies[$name]);
        } elseif (isset($dependencies[$name])) {
            return $dependencies[$name];
        }

        return new $class($board);
    }
}
