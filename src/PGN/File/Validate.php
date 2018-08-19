<?php

namespace PGNChess\PGN\File;

use PGNChess\PGN\Validate as PgnValidate;

/**
 * Validate class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Validate extends AbstractFile
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
     * Checks if the syntax of a PGN file is valid.
     *
     * @return mixed array|bool
     */
    public function syntax()
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
