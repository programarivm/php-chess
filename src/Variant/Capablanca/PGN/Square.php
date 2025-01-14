<?php

namespace Chess\Variant\Capablanca\PGN;

use Chess\Variant\Classical\PGN\Square as ClassicalSquare;

class Square extends ClassicalSquare
{
    const REGEX = '[a-j]{1}[1-8]{1}';

    const SIZE = [
        'files' => 10,
        'ranks' => 8,
    ];

    const EXTRACT = '/[^a-j1-8 "\']/';
}
