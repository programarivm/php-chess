<?php

namespace Chess\Tutor;

use Chess\Function\FunctionInterface;
use Chess\Variant\AbstractBoard;

abstract class AbstractParagraph
{
    public FunctionInterface $f;

    public AbstractBoard $board;

    public array $paragraph = [];
}
