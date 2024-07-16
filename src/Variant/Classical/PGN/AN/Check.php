<?php

namespace Chess\Variant\Classical\PGN\AN;

use Chess\Variant\AbstractNotation;

class Check extends AbstractNotation
{
    const REGEX = '[\+\#]{0,1}';
}
