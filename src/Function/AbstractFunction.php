<?php

namespace Chess\Function;

abstract class AbstractFunction
{
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
