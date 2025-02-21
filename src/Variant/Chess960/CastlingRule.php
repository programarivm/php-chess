<?php

namespace Chess\Variant\Chess960;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\RandomCastlingRuleTrait;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Classical\CastlingRule as ClassicalCastlingRule;

class CastlingRule extends ClassicalCastlingRule
{
    use RandomCastlingRuleTrait;

    public function __construct(array $shuffle = [])
    {
        $this->shuffle = $shuffle;

        for ($i = 0; $i < count($this->shuffle); $i++) {
            $this->startFiles[chr(97 + $i)] = $this->shuffle[$i];
        }

        $this->size = Square::SIZE;

        $this->rule = (new ClassicalCastlingRule())->rule;

        $this->sq()->moveSqs();
    }

    public function validate(string $value): string
    {
        if ($value === self::NEITHER) {
            return $value;
        } elseif ($value && preg_match('/^K?Q?k?q?$/', $value)) {
            return $value;
        }

        throw new UnknownNotationException();
    }
}
