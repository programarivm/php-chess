<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\RandomCastlingRuleTrait;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\CastlingRule as ClassicalCastlingRule;

class CastlingRule extends ClassicalCastlingRule
{
    use RandomCastlingRuleTrait;

    public function __construct(array $shuffle)
    {
        $this->shuffle = $shuffle;

        for ($i = 0; $i < count($this->shuffle); $i++) {
            $this->startFiles[chr(97 + $i)] = $this->shuffle[$i];
        }

        $this->size = Square::SIZE;

        $this->rule = (new ClassicalCastlingRule())->rule;

        $this->sq()->moveSqs();
    }
}
