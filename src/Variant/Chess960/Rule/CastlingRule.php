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

        $this->startFiles = [
            'a' => $this->startPos[0],
            'b' => $this->startPos[1],
            'c' => $this->startPos[2],
            'd' => $this->startPos[3],
            'e' => $this->startPos[4],
            'f' => $this->startPos[5],
            'g' => $this->startPos[6],
            'h' => $this->startPos[7],
        ];

        $this->rule = (new ClassicalCastlingRule())->getRule();

        $this->sq()->sqs();
    }
}
