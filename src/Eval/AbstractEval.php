<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

/**
 * Abstract evaluation.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
abstract class AbstractEval
{
    protected Board $board;

    protected array $value;

    protected array $result;

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
    }
}
