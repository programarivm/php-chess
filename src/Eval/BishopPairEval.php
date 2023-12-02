<?php

namespace Chess\Eval;

use Chess\Tutor\ColorPhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class BishopPairEval extends AbstractEval
{
    const NAME = 'Bishop pair';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $count = [
            Color::W => 0,
            Color::B => 0,
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::B) {
                $count[$piece->getColor()] += 1;
            }
        }

        if ($count[Color::W] === 2 && $count[Color::W] > $count[Color::B]) {
            $this->result[Color::W] = 1;
            $this->explain(Color::W);
        } elseif ($count[Color::B] === 2 && $count[Color::B] > $count[Color::W]) {
            $this->result[Color::B] = 1;
            $this->explain(Color::B);
        }
    }

    private function explain($subject, $target = null)
    {
        $phrase = ColorPhrase::deterministic($subject);
        $this->explanation[] = "{$phrase} has the bishop pair.";

        return $this->explanation;
    }
}
