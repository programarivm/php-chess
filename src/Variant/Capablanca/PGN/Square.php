<?php

namespace Chess\Variant\Capablanca\PGN;

use Chess\Variant\Classical\PGN\Square as ClassicalSquare;

class Square extends ClassicalSquare
{
    /**
     * Regular expression representing a square in algebraic notation.
     *
     * @var string
     */
    const AN = '[a-j]{1}[1-8]{1}';

    /**
     * Regular expression representing a square for further extraction from strings.
     *
     * @var string
     */
    const EXTRACT = '/[^a-j1-8 "\']/';

    /**
     * The size of the chess board.
     *
     * @var array
     */
    const SIZE = [
        'files' => 10,
        'ranks' => 8,
    ];
}
