<?php

namespace Chess\ML;

abstract class AbstractLabeller
{
    abstract public function label(array $end);
}
