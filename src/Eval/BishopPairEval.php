<?php

namespace Chess\Eval;

use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class BishopPairEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Bishop pair';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has the bishop pair",
        ];

        $count = [
            Color::W => 0,
            Color::B => 0,
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->id === Piece::B) {
                $count[$piece->color] += 1;
            }
        }

        if ($count[Color::W] === 2 && $count[Color::W] > $count[Color::B]) {
            $this->result[Color::W] = 1;
        } elseif ($count[Color::B] === 2 && $count[Color::B] > $count[Color::W]) {
            $this->result[Color::B] = 1;
        }

        $this->explain($this->result);
    }
}
