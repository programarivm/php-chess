<?php

namespace Chess\Computer;

use Chess\Variant\Classical\Board;

/**
 * AbstractComputer
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
abstract class AbstractComputer
{
    /**
     * Returns a chess move.
     *
     * @param \Chess\Variant\Classical\Board $board
     * @return null|object
     */
    abstract public function move(Board $board): ?object;
}
