<?php

namespace PGNChess\Exception;

use PGNChess\Exception;

/**
 * Thrown when a pgn file syntax exception occurs.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
final class PgnFileSyntaxException extends \InvalidArgumentException implements Exception
{
    private $result;

    public function __construct(string $message, \stdClass $result)
    {
        $this->result = $result;
    }

    public function getResult(): \stdClass
    {
        return $this->result;
    }
}
