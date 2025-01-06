<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Center Evaluation
 *
 * Measures how close the pieces are to the center of the board.
 */
class CenterEval extends AbstractEval implements UniqueImbalanceEvalInterface
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
     * slightly tilted. Please note how each square is assigned a unique slope.
     *
     * The board is divided into four main sections, each of which is assigned
     * an integer value starting from 0. The outermost section is assigned a
     * slope of 0. Moving clockwise, a constant value is then added to each
     * square to create the sufficiently small imbalance. This constant value
     * will never exceed 0.10000 after completing a rotation.
     *
     * So for example, to each square of the outermost section, 10.000 / (8 x 4)
     * ≈ 300 is added. To each square of the innermost section, 10.000 / 4
     * ≈ 2500 is added.
     *
     * @var array
     */
    private array $center = [
        'a8' => 0.00300, 'b8' => 0.00600, 'c8' => 0.00900, 'd8' => 0.01200, 'e8' => 0.01500, 'f8' => 0.01800, 'g8' => 0.02100, 'h8' => 0.02400,
        'a7' => 0.08400, 'b7' => 1.00400, 'c7' => 1.00800, 'd7' => 1.01200, 'e7' => 1.01600, 'f7' => 1.02000, 'g7' => 1.02400, 'h7' => 0.02700,
        'a6' => 0.08100, 'b6' => 1.08000, 'c6' => 2.00600, 'd6' => 2.01200, 'e6' => 2.01800, 'f6' => 2.02400, 'g6' => 1.02800, 'h6' => 0.03000,
        'a5' => 0.07800, 'b5' => 1.07600, 'c5' => 2.07200, 'd5' => 3.00000, 'e5' => 3.02500, 'f5' => 2.03000, 'g5' => 1.03200, 'h5' => 0.03300,
        'a4' => 0.07500, 'b4' => 1.07200, 'c4' => 2.06600, 'd4' => 3.07500, 'e4' => 3.05000, 'f4' => 2.03600, 'g4' => 1.03600, 'h4' => 0.03600,
        'a3' => 0.07200, 'b3' => 1.06800, 'c3' => 2.06000, 'd3' => 2.05400, 'e3' => 2.04800, 'f3' => 2.04200, 'g3' => 1.04000, 'h3' => 0.03900,
        'a2' => 0.06900, 'b2' => 1.06400, 'c2' => 1.06000, 'd2' => 1.05600, 'e2' => 1.05200, 'f2' => 1.04800, 'g2' => 1.04400, 'h2' => 0.04200,
        'a1' => 0.06600, 'b1' => 0.06300, 'c1' => 0.06000, 'd1' => 0.05700, 'e1' => 0.05400, 'f1' => 0.05100, 'g1' => 0.04800, 'h1' => 0.04500,
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
