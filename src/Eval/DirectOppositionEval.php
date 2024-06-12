<?php

namespace Chess\Eval;

use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class DirectOppositionEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Direct opposition';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'The white king',
            'The black king',
        ];

        $this->observation = [
            "has the direct opposition preventing the advance of the other king",
        ];

        $wKSq = $this->board->getPiece(Color::W, Piece::K)->sq;
        $bKSq = $this->board->getPiece(Color::B, Piece::K)->sq;

        if ($wKSq[0] === $bKSq[0]) {
            if (abs($wKSq[1] - $bKSq[1]) === 2) {
                $this->result = [
                    Color::W => (int) ($this->board->turn !== Color::W),
                    Color::B => (int) ($this->board->turn !== Color::B),
                ];
            }
        } elseif ($wKSq[1] === $bKSq[1]) {
            if (abs(ord($wKSq[0]) - ord($bKSq[0])) === 2) {
                $this->result = [
                    Color::W => (int) ($this->board->turn !== Color::W),
                    Color::B => (int) ($this->board->turn !== Color::B),
                ];
            }
        }

        $this->explain($this->result);
    }
}
