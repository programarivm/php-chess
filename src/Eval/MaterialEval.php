<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Material Evaluation
 *
 * The player with the most material points has an advantage. The relative
 * values of the pieces are assigned this way: 1 point to a pawn, 3.2 points to
 * a knight, 3.33 points to a bishop, 5.1 points to a rook and 8.8 points to a
 * queen.
 *
 * @see \Chess\Eval\AbstractEval
 */
class MaterialEval extends AbstractEval
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Material';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 4];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight material advantage",
            "has a moderate material advantage",
            "has a decisive material advantage",
        ];

        foreach ($this->board->pieces(Color::W) as $piece) {
            if ($piece->id !== Piece::K) {
                $this->result[Color::W] += self::$value[$piece->id];
            }
        }

        foreach ($this->board->pieces(Color::B) as $piece) {
            if ($piece->id !== Piece::K) {
                $this->result[Color::B] += self::$value[$piece->id];
            }
        }

        $this->result[Color::W] = round($this->result[Color::W], 4);
        $this->result[Color::B] = round($this->result[Color::B], 4);
    }
}
