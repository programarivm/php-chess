<?php

namespace PGNChess\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\PGN\File\Syntax as PgnFileSyntax;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate;

/**
 * ToMySql class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class ToMySql
{
    private $filepath;

    public function __construct($filepath)
    {
        (new PgnFileSyntax)->check($filepath);

        $this->filepath = $filepath;
    }

    /**
     * Converts a pgn file into MySql code.
     *
     * Precondition: the input file is valid pgn.
     *
     * @return string The MySQL code
     * @throws \PGNChess\Exception\UnknownNotationException
     */
    public function convert()
    {
        $sql = 'INSERT INTO games (';
        foreach (Tag::getConstants() as $key => $value) {
            $sql .= $value.', ';
        }
        $sql .= 'movetext) VALUES (';

        $tags = $this->resetTags();
        $movetext = '';

        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                try {
                    $tag = Validate::tag($line);
                    $tags[$tag->name] = $tag->value;
                } catch (\Exception $e) {
                    if ($this->startsMovetext($line)) {
                        $movetext .= $line;
                    } elseif ($this->endsMovetext($line)) {
                        foreach ($tags as $key => $value) {
                            isset($value) ? $sql .= "'".MySql::getInstance()->escape($value)."', " : $sql .= 'null, ';
                        }
                        $movetext = MySql::getInstance()->escape($movetext.$line);
                        $sql .= "'$movetext'),(";
                        $tags = $this->resetTags();
                        $movetext = '';
                    } else {
                        $movetext .= $line;
                    }
                }
            }
            fclose($file);
        }
        $sql = substr($sql, 0, -2).';'.PHP_EOL;

        return $sql;
    }

    private function startsMovetext($line)
    {
        return $this->startsWith($line, '1.');
    }

    private function endsMovetext($line)
    {
        return $this->endsWith($line, '0-1') || $this->endsWith($line, '1-0') || $this->endsWith($line, '1/2-1/2');
    }

    public function startsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, 0, strlen($needle)), $needle) === 0;
    }

    private function endsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0;
    }

    private function resetTags()
    {
        foreach (Tag::getConstants() as $key => $value) {
            $tags[$value] = null;
        }

        return $tags;
    }
}
