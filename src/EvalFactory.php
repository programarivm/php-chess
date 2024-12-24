<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;

class EvalFactory
{
    public static function create(AbstractFunction $f, string $name, AbstractBoard $board)
    {
        foreach ($f->eval as $val) {
            $class = new \ReflectionClass($val);
            if ($name === $class->getConstant('NAME')) {
                return $class->newInstanceArgs([$board]);
            }
        }

        throw new \InvalidArgumentException();
    }
}
