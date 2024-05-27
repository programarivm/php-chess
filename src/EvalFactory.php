<?php

namespace Chess;

use Chess\Function\StandardFunction;
use Chess\Variant\Classical\Board;

/**
 * Factory of chess evaluation objects.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class EvalFactory
{
    /**
     * Creates an evaluation object.
     *
     * @param string $name
     * @param \Chess\Variant\Classical\Board $board
     */
    public static function create(string $name, Board $board)
    {
        $function = new StandardFunction();
        foreach ($function->getEval() as $key => $val) {
            $class = new \ReflectionClass($key);
            if ($name === $class->getConstant('NAME')) {
                return $class->newInstanceArgs([$board]);
            }
        }

        throw new \InvalidArgumentException();
    }
}
