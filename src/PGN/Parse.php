<?php

namespace PGNChess\PGN;

use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate;

/**
 * Parse class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Parse
{
	private $filepath;

	public function __construct($filepath)
	{
		$this->filepath = $filepath;
	}

	public function toSql()
	{
		$values = '(';
		if ($file = fopen($this->filepath, "r")) {
			while(!feof($file)) {
				$line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
				try {
					$tag = Validate::tag($line);
					if (isset($tag->name)) {
						$values .= "'$tag->value', ";;
					}
				} catch (\Exception $e) {
					if ($this->startsMovetext($line)) {
						$values .=  "'$line";
					} elseif ($this->endsMovetext($line)) {
                        $values .= "$line'),(";
					} else {
						$values .= $line;
					}
				}
			}
			fclose($file);
		}

        $values = substr($values, 0, -2) . ';' . PHP_EOL;

		return $values;
	}

	private function startsMovetext($line)
	{
		return ($this->startsWith($line, '1.'));
	}

	private function endsMovetext($line)
	{
		return ($this->endsWith($line, '0-1') || $this->endsWith($line, '1-0') || $this->endsWith($line, '1/2-1/2'));
	}

	function startsWith($haystack, $needle)
	{
		return (strcasecmp(substr($haystack, 0, strlen($needle)), $needle) === 0);
	}

	private function endsWith($haystack, $needle)
	{
		return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0);
	}
}
