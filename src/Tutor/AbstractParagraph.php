<?php

namespace Chess\Tutor;

use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;

abstract class AbstractParagraph
{
    public AbstractFunction $function;

    public AbstractBoard $board;

    public array $paragraph = [];
}
