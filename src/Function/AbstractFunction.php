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
        foreach ($this->eval as $key => $val) {
            $names[] = (new \ReflectionClass($key))->getConstant('NAME');
        }

        return $names;
    }

    /**
     * Returns the evaluation weights.
     *
     * @return array
     */
    public function weights(): array
    {
        return array_values($this->eval);
    }
}
