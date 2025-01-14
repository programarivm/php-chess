<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Bishop Pair Evaluation
 *
 * The player with both bishops may definitely have an advantage, especially
 * in the endgame. Furthermore, two bishops can deliver checkmate.
 */
class BishopPairEval extends AbstractEval
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Bishop pair';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
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

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::B) {
                $count[$piece->color] += 1;
            }
        }

        if ($count[Color::W] === 2 && $count[Color::W] > $count[Color::B]) {
            $this->result[Color::W] = 1;
        } elseif ($count[Color::B] === 2 && $count[Color::B] > $count[Color::W]) {
            $this->result[Color::B] = 1;
        }
    }
}
