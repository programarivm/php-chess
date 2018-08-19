<?php

namespace PGNChess\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\Exception\InvalidPgnFileSyntaxException;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChess\PGN\File\Validate as PgnFileValidate;

/**
 * Convert class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Convert extends AbstractFile
{
    /**
     * Constructor.
     *
     * @throws \PGNChess\Exception\InvalidPgnFileSyntaxException
     */
    public function __construct($filepath)
    {
        parent::__construct($filepath);

        $result = (new PgnFileValidate($filepath))->syntax();
        if ($result->valid === 0 || !empty($result->errors)) {
            throw new InvalidPgnFileSyntaxException('Invalid PGN file.', $result->errors);
        }
    }

    /**
     * Converts a valid pgn file into a MySQL INSERT statement.
     *
     * @return string The MySQL code
     */
    public function toMySql()
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
                    $tag = PgnValidate::tag($line);
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
}
