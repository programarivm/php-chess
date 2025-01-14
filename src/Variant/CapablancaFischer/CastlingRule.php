<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomCastlingRuleTrait;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Capablanca\CastlingRule as CapablancaCastlingRule;

class CastlingRule extends CapablancaCastlingRule
{
    use RandomCastlingRuleTrait;

    public function __construct(array $startPos)
    {
        $this->startPos = $startPos;

        for ($i = 0; $i < count($this->startPos); $i++) {
            $this->startFiles[chr(97 + $i)] = $this->startPos[$i];
        }

        $this->size = Square::SIZE;

        $this->rule = (new CapablancaCastlingRule())->rule;

        $this->sq()->moveSqs();
    }
}
