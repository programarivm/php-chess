<?php

namespace Chess\Function;

/**
 * AbstractFunction
 *
 * Abstract evaluation function.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class AbstractFunction
{
    /**
     * The evaluation features.
     *
     * @var array
     */
    protected array $eval;

    /**
     * Returns the evaluation features.
     *
     * @return array
     */
    public function getEval(): array
    {
        return $this->eval;
    }

    /**
     * Returns the evaluation names.
     *
     * @return array
     */
    public function names(): array
    {
        foreach ($this->eval as $val) {
            $names[] = (new \ReflectionClass($val))->getConstant('NAME');
        }

        return $names;
    }
}
