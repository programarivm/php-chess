<?php

namespace Chess\Tutor;

use Chess\Eval\AbstractFunction;
use Chess\Variant\AbstractBoard;

abstract class AbstractParagraph
{
    public AbstractFunction $f;

    public AbstractBoard $board;

    public array $paragraph = [];

    public function futurize(): array
    {
        foreach ($this->paragraph as &$val) {
            $val = preg_replace('/\bare\b/u', 'will be', $val);
            $val = preg_replace('/\bcan\b/u', 'will be able to', $val);
            $val = preg_replace('/\bhas\b/u', 'will have', $val);
            $val = preg_replace('/\bis\b/u', 'will be', $val);
        }

        return $this->paragraph;
    }
}
