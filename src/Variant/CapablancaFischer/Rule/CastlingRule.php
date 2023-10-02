<?php

namespace Chess\Variant\CapablancaFischer\Rule;

use Chess\Variant\RandomCastlingRuleTrait;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Capablanca\Rule\CastlingRule as CapablancaCastlingRule;

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

        $this->rule = (new CapablancaCastlingRule())->getRule();

        $this->sq()->sqs();
    }
}
