<?php

namespace Chess\Eval;

use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\Board;

/**
 * Abstract evaluation.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
abstract class AbstractEval
{
    protected Board $board;

    protected array $value;

    protected array $result;

    protected array $explanation = [];

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->value = [
            Piece::A => 6.53,
            Piece::B => 3.33,
            Piece::C => 8.3,
            Piece::K => 4,
            Piece::N => 3.2,
            Piece::P => 1,
            Piece::Q => 8.8,
            Piece::R => 5.1,
        ];

        $this->result = [
            Color::W => 0,
            Color::B => 0,
        ];
    }

    public function explanation()
    {
        return $this->explanation;
    }
}
