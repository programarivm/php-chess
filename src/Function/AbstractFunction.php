<?php

namespace Chess\Function;

abstract class AbstractFunction
{
    public function names(): array
    {
        foreach ($this->eval as $val) {
            $names[] = (new \ReflectionClass($val))->getConstant('NAME');
        }

        return $names;
    }
}
