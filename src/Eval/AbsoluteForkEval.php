<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\Board;

class AbsoluteForkEval extends AbstractForkEval
{
    public const NAME = 'Absolute fork';

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
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->isAttackingKing()) {
                $this->result[$piece->getColor()] =
                    $this->sumValues($piece, $piece->attackedPieces());
            }
        }

        return $this->result;
    }
}
