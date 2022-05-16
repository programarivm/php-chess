<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;

class BishopPairEvaluation extends AbstractEvaluation
{
    const NAME = 'bishop_pair';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Color::W => 0,
            Color::B => 0,
        ];
    }

    public function eval(): array
    {
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
        } elseif ($count[Color::B] === 2 && $count[Color::B] > $count[Color::W]) {
            $this->result[Color::B] = 1;
        }

        return $this->result;
    }
}
