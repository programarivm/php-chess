<?php
namespace PGNChess\Exception;

use PGNChess\Exception;

/**
 * Thrown when the file's character encoding is not UTF-8.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
final class PgnFileCharacterEncodingException extends \DomainException implements Exception
{

}
