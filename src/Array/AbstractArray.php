<?php

namespace Chess\Array;

/**
 * Abstract array.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractArray
{
    /**
     * Array.
     *
     * @var array
     */
    protected array $array;

    /**
     * Constructor.
     *
     * @param array $array
     */
    abstract public function __construct(array $array);

    /**
     * Returns the array.
     *
     * @return array
     */
     public function getArray(): array
     {
         return $this->array;
     }
}
