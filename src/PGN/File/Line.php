<?php

namespace PGNChess\PGN\File;

use PGNChess\PGN\Symbol;
use PGNChess\PGN\Tag;

class Line
{
    public function isMovetext($line)
    {
        return $this->startsMovetext($line) && $this->endsMovetext($line);
    }

    public function startsMovetext($line)
    {
        return $this->startsWith($line, '1.');
    }

    public function endsMovetext($line)
    {
        return $this->endsWith($line, Symbol::RESULT_WHITE_WINS) ||
            $this->endsWith($line, Symbol::RESULT_BLACK_WINS) ||
            $this->endsWith($line, Symbol::RESULT_DRAW) ||
            $this->endsWith($line, Symbol::RESULT_UNKNOWN);
    }

    public function startsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, 0, strlen($needle)), $needle) === 0;
    }

    public function endsWith($haystack, $needle)
    {
        return strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0;
    }
}
