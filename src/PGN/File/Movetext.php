<?php

namespace PGNChess\PGN\File;

/**
 * Movetext.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Movetext extends AbstractFile
{
    public function __construct($filepath)
    {
        parent::__construct($filepath);
    }

    public function toString()
    {
        $movetext = '';
        if ($file = fopen($this->filepath, 'r')) {
            while (!feof($file)) {
                $line = preg_replace('~[[:cntrl:]]~', '', fgets($file));
                $movetext .= ' ' . $line;
            }
            fclose($file);
        }

        return trim($movetext);
    }
}
