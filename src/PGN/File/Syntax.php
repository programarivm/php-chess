<?php

namespace PGNChess\PGN\File;

use PGNChess\PGN\Validate;

/**
 * Syntax class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Syntax extends AbstractFile
{
    private $invalid;

    public function __construct($filepath)
    {
        parent::__construct($filepath);

        $invalid = [];
    }

    /**
     * Checks if the syntax of a PGN file is valid.
     *
     * @return mixed array|bool
     */
    public function check()
    {
        $tags = $this->resetTags();
        $movetext = '';

        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                try {
                    $tag = Validate::tag($line);
                    $tags[$tag->name] = $tag->value;
                } catch (\Exception $e) {
                    if ($this->startsMovetext($line) && !$this->hasStrTags($tags)) {
                        $this->invalid[] = $tags;
                        $tags = $this->resetTags();
                        $movetext = '';
                    } elseif ($this->startsMovetext($line) && $this->hasStrTags($tags)) {
                        $movetext .= $line;
                    } elseif ($this->endsMovetext($line)) {
                        $movetext .= $line;
                        // play movetext
                        $tags = $this->resetTags();
                        $movetext = '';
                    } else {
                        $movetext .= $line;
                    }
                }
            }
            fclose($file);
        }

        return $this->invalid;
    }
}
