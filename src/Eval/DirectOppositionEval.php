<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Direct Opposition Evaluation
 *
 * A position in which the kings are facing each other being two squares apart
 * on the same rank or file. In this situation, the player not having to move is
 * said to have the opposition.
 */
class DirectOppositionEval extends AbstractEval
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Direct opposition';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
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

        $wKSq = $this->board->piece(Color::W, Piece::K)->sq;
        $bKSq = $this->board->piece(Color::B, Piece::K)->sq;

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
    }
}
