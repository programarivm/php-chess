<?php

namespace Chess\Parser;

use Chess\Variant\Classical\PGN\Termination;

class PgnLine
{
    public function isOneLinerMovetext(string $line): bool
    {
        return $this->startsMovetext($line) && $this->endsMovetext($line);
    }

    public function startsMovetext(string $line): bool
    {
        return $this->startsWith($line, '1.');
    }

    public function endsMovetext(string $line): bool
    {
        return $this->endsWith($line, Termination::WHITE_WINS) ||
            $this->endsWith($line, Termination::BLACK_WINS) ||
            $this->endsWith($line, Termination::DRAW) ||
            $this->endsWith($line, Termination::UNKNOWN);
    }

    public function startsWith(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    public function endsWith(string $haystack, string $needle): bool
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}
