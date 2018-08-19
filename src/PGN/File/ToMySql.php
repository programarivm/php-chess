<?php

namespace PGNChess\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\PGN\File\Validate as PgnFileValidate;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;

/**
 * ToMySql class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class ToMySql extends AbstractFile
{
    /**
     * Constructor.
     *
     * @throws \PGNChess\Exception\UnknownNotationException
     */
    public function __construct($filepath)
    {
        parent::__construct($filepath);

        (new PgnFileValidate($filepath))->syntax();
    }

    /**
     * Converts a pgn file into MySql code.
     *
     * Precondition: the input file is valid pgn.
     *
     * @return string The MySQL code
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
