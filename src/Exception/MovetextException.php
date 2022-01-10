<?php

namespace Chess\Exception;

use Chess\Exception;

/**
 * Thrown when dealing with an unknown PGN movetext notation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
final class MovetextException extends \InvalidArgumentException implements Exception
{

}
