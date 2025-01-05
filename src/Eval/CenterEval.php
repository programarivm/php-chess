<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Center Evaluation
 *
 * Measures how close the pieces are to the center of the board.
 */
class CenterEval extends AbstractEval implements UniqueEvalInterface
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Center';

    /**
     * The closer a square is to the center, the higher its value. The board is
     * slightly tilted. Please note how each square is assigned a different
     * value to break the vertical symmetry and make sure that each move can
     * create a unique imbalance.
     *
     * @var array
     */
    private array $center = [
        'a8' => 0.004, 'b8' => 0.003, 'c8' => 0.002, 'd8' => 0.001, 'e8' => 0.028, 'f8' => 0.027, 'g8' => 0.026, 'h8' => 0.025,
        'a7' => 0.005, 'b7' => 1.003, 'c7' => 1.002, 'd7' => 1.001, 'e7' => 1.020, 'f7' => 1.019, 'g7' => 1.018, 'h7' => 0.024,
        'a6' => 0.006, 'b6' => 1.004, 'c6' => 2.002, 'd6' => 2.001, 'e6' => 2.012, 'f6' => 2.011, 'g6' => 1.017, 'h6' => 0.023,
        'a5' => 0.007, 'b5' => 1.005, 'c5' => 2.003, 'd5' => 3.001, 'e5' => 3.004, 'f5' => 2.010, 'g5' => 1.016, 'h5' => 0.022,
        'a4' => 0.008, 'b4' => 1.006, 'c4' => 2.004, 'd4' => 3.002, 'e4' => 3.003, 'f4' => 2.009, 'g4' => 1.015, 'h4' => 0.021,
        'a3' => 0.009, 'b3' => 1.007, 'c3' => 2.005, 'd3' => 2.006, 'e3' => 2.007, 'f3' => 2.008, 'g3' => 1.014, 'h3' => 0.020,
        'a2' => 0.010, 'b2' => 1.008, 'c2' => 1.009, 'd2' => 1.010, 'e2' => 1.011, 'f2' => 1.012, 'g2' => 1.013, 'h2' => 0.019,
        'a1' => 0.011, 'b1' => 0.012, 'c1' => 0.013, 'd1' => 0.014, 'e1' => 0.015, 'f1' => 0.016, 'g1' => 0.017, 'h1' => 0.018,
    ];

    /**
     * @param \Chess\Variant\AbstractBoard $board
     *
     * The value of each piece occupying a square is considered in the total sum
     * of the result. The more valuable the piece, the better. To this sum are
     * also added the squares controlled by each player. The controlled squares
     * are those in each player's space.
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 9];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slightly better control of the center",
            "has a moderate control of the center",
            "is totally controlling the center",
        ];

        foreach ($this->center as $sq => $val) {
            if ($piece = $this->board->pieceBySq($sq)) {
                $this->result[$piece->color] += floor(self::$value[$piece->id] * $val);
            }
            if (in_array($sq, $this->board->spaceEval[Color::W])) {
                $this->result[Color::W] += $val;
            }
            if (in_array($sq, $this->board->spaceEval[Color::B])) {
                $this->result[Color::B] += $val;
            }
        }

        $this->result[Color::W] = round($this->result[Color::W], 5);
        $this->result[Color::B] = round($this->result[Color::B], 5);
    }
}
