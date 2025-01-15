<?php

namespace Chess\Function;

trait FunctionTrait
{
    public static function names(): array
    {   
        foreach (self::$eval as $val) {
            $names[] = (new \ReflectionClass($val))->getConstant('NAME');
        }

        return $names;
    }
}
