<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Capablanca\PGN\Piece;
use Chess\Variant\Classical\PGN\Color;

abstract class AbstractEval
{
    public static $value = [
        Piece::A => 6.53,
        Piece::B => 3.33,
        Piece::C => 8.3,
        Piece::K => 4,
        Piece::N => 3.2,
        Piece::P => 1,
        Piece::Q => 8.8,
        Piece::R => 5.1,
    ];

    public AbstractBoard $board;

    public array $result = [
        Color::W => 0,
        Color::B => 0,
    ];
}
