<?php

namespace Chess\Tutor;

use Chess\Eval\AbstractFunction;
use Chess\Variant\AbstractBoard;

abstract class AbstractParagraph
{
    public AbstractFunction $f;

    public AbstractBoard $board;

    public array $paragraph = [];
}
