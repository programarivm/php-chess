<?php

namespace PGNChess\PGN\File;

use PGNChess\Db\MySql;
use PGNChess\Exception\InvalidPgnFileSyntaxException;
use PGNChess\PGN\Tag;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChess\PGN\File\Validate as PgnFileValidate;

/**
 * Seed class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Seed extends AbstractFile
{
    private $result = [];

    public function __construct($filepath)
    {
        parent::__construct($filepath);

        $this->result = (object) [
            'valid' => 0,
            'errors' => []
        ];
    }

    /**
     * Converts a valid pgn file into a MySQL INSERT statement.
     *
     * @return string The MySQL code
     */
     public function db()
     {
         $tags = $this->resetTags();
         $movetext = '';
         if ($file = fopen($this->filepath, 'r')) {
             while (!feof($file)) {
                 $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                 try {
                     $tag = PgnValidate::tag($line);
                     $tags[$tag->name] = $tag->value;
                 } catch (\Exception $e) {
                     switch (true) {
                         case !$this->hasStrTags($tags) && $this->startsMovetext($line):
                             $this->result->errors[] = ['tags' => array_filter($tags)];
                             $tags = $this->resetTags();
                             $movetext = '';
                             break;
                         case $this->hasStrTags($tags) &&
                             (($this->startsMovetext($line) && $this->endsMovetext($line)) || $this->endsMovetext($line)):
                             $movetext .= ' ' . $line;
                             if (!PgnValidate::movetext($movetext)) {
                                 $this->result->errors[] = [
                                     'tags' => array_filter($tags),
                                     'movetext' => trim($movetext)
                                 ];
                             } else {
                                $this->result->valid += 1;
                                $sql = 'INSERT INTO games (';
                                foreach (Tag::getConstants() as $key => $value) {
                                    $sql .= $value.', ';
                                }
                                $sql .= 'movetext) VALUES (';
                                foreach ($tags as $key => $value) {
                                    isset($value) ? $sql .= "'".MySql::getInstance()->escape($value)."', " : $sql .= 'null, ';
                                }
                                $movetext = MySql::getInstance()->escape($movetext.$line);
                                $sql .= "'" . trim($movetext) . "')";
                                MySql::getInstance()->query($sql);
                             }
                             $tags = $this->resetTags();
                             $movetext = '';
                             break;
                         case $this->hasStrTags($tags):
                             $movetext .= ' ' . $line;
                             break;
                     }
                 }
             }
             fclose($file);
         }

         if (empty($this->result->errors)) {
             unset($this->result->errors);
         }

         return $this->result;
     }
}
