<?php

namespace PGNChess\PGN\File;

use PGNChess\Exception\PgnFileCharacterEncodingException;

abstract class AbstractFile
{
    protected $filepath;

    protected $line;

    public function __construct(string $filepath)
    {
        $content = file_get_contents($filepath);
        $encoding = mb_detect_encoding($content);

        if ($encoding !== 'ASCII' && $encoding !== 'UTF-8') {
            throw new PgnFileCharacterEncodingException(
                "Character encoding detected: $encoding. Needs to be UTF-8."
            );
        }

        $this->filepath = $filepath;
        $this->line = new Line;
    }
}
