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
final class InvalidPgnFileSyntaxException extends \InvalidArgumentException implements Exception
{
    private $result;

    public function __construct($message, $result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }
}
