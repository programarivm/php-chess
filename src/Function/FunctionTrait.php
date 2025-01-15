<?php

namespace Chess\Function;

use Chess\Variant\AbstractBoard;

trait FunctionTrait
{
    public static function names(): array
    {   
        foreach (self::$eval as $val) {
            $names[] = (new \ReflectionClass($val))->getConstant('NAME');
        }

        return $names;
    }

    public static function evaluate(string $name, AbstractBoard $board)
    {
        foreach (self::$eval as $val) {
            $class = new \ReflectionClass($val);
            if ($name === $class->getConstant('NAME')) {
                return $class->newInstanceArgs([$board]);
            }
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Returns an array of normalized values.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return array
     */
    public static function normalization(AbstractBoard $board): array
    {
        $items = [];
        foreach (self::names() as $val) {
            $item = self::add(self::evaluate($val, $board));
            $items[] =  $item[Color::W] - $item[Color::B];
        }

        return self::normalize(-1, 1, $items);
    }
}
