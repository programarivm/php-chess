<?php

namespace PGNChess\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\Exception\PgnFileSyntaxException;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChess\PGN\File\Validate as PgnFileValidate;

class Convert extends AbstractFile
{
    public function __construct($filepath)
    {
        parent::__construct($filepath);

        $result = (new PgnFileValidate($filepath))->syntax();

        if ($result->valid === 0 || !empty($result->errors)) {
            throw new PgnFileSyntaxException('Invalid PGN file.', $result->errors);
        }
    }

    public function toMySqlScript()
    {
        $sql = 'INSERT INTO games (';
        foreach (Tag::getConstants() as $key => $value) {
            $sql .= $value.', ';
        }
        $sql .= 'movetext) VALUES (';

        $tags = [];
        $movetext = '';

        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                try {
                    $tag = PgnValidate::tag($line);
                    $tags[$tag->name] = $tag->value;
                } catch (\Exception $e) {
                    if ($this->line->startsMovetext($line)) {
                        $movetext .= $line;
                    } elseif ($this->line->endsMovetext($line)) {
                        foreach ($tags as $key => $value) {
                            isset($value) ? $sql .= "'".MySql::getInstance()->escape($value)."', " : $sql .= 'null, ';
                        }
                        $movetext = MySql::getInstance()->escape($movetext.$line);
                        $sql .= "'$movetext'),(" . PHP_EOL;
                        Tag::reset($tags);
                        $movetext = '';
                    } else {
                        $movetext .= $line;
                    }
                }
            }
            fclose($file);
        }
        $sql = substr($sql, 0, -3).';'.PHP_EOL;

        return $sql;
    }
}
