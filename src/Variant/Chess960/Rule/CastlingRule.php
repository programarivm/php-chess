<?php

namespace Chess\Variant\Chess960\Rule;

use Chess\Variant\RandomCastlingRuleTrait;
use Chess\Variant\Classical\Rule\CastlingRule as ClassicalCastlingRule;

class CastlingRule extends ClassicalCastlingRule
{
    use RandomCastlingRuleTrait;

    private array $startPos;

    private array $startFiles;

    public function __construct(array $startPos)
    {
        $this->startPos = $startPos;

        for ($i = 0; $i < count($this->startPos); $i++) {
            $this->startFiles[chr(97 + $i)] = $this->startPos[$i];
        }

        $this->rule = (new ClassicalCastlingRule())->getRule();

        $this->sq()->sqs();
    }
}
