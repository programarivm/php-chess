<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Variant\AbstractNotation;

class Check extends AbstractNotation
{
    const REGEX = '[\+\#]{0,1}';
}
