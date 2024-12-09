<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;

class EvalFactory
{
    public static function create(AbstractFunction $function, string $name, AbstractBoard $board)
    {
        foreach ($function->getEval() as $key => $val) {
            $class = new \ReflectionClass($key);
            if ($name === $class->getConstant('NAME')) {
                return $class->newInstanceArgs([$board]);
            }
        }

        throw new \InvalidArgumentException();
    }
}
