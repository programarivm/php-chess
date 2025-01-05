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
     * value to break the vertical symmetry and make sure that each move creates
     * a unique imbalance.
     *
     * @var array
     */
    private array $center = [
        'a8' => 0.00750, 'b8' => 0.00500, 'c8' => 0.00250, 'd8' => 0.00000, 'e8' => 0.06750, 'f8' => 0.06500, 'g8' => 0.06250, 'h8' => 0.06000,
        'a7' => 0.01000, 'b7' => 1.01000, 'c7' => 1.00500, 'd7' => 1.00000, 'e7' => 1.09500, 'f7' => 1.09000, 'g7' => 1.08500, 'h7' => 0.05750,
        'a6' => 0.01250, 'b6' => 1.01500, 'c6' => 2.00500, 'd6' => 2.00000, 'e6' => 2.05500, 'f6' => 2.05000, 'g6' => 1.08000, 'h6' => 0.05500,
        'a5' => 0.01500, 'b5' => 1.02000, 'c5' => 2.01000, 'd5' => 3.00000, 'e5' => 3.03000, 'f5' => 2.04500, 'g5' => 1.07500, 'h5' => 0.05250,
        'a4' => 0.01750, 'b4' => 1.02500, 'c4' => 2.01500, 'd4' => 3.01000, 'e4' => 3.02000, 'f4' => 2.04000, 'g4' => 1.07000, 'h4' => 0.05000,
        'a3' => 0.02000, 'b3' => 1.03000, 'c3' => 2.02000, 'd3' => 2.02500, 'e3' => 2.03000, 'f3' => 2.03500, 'g3' => 1.06500, 'h3' => 0.04750,
        'a2' => 0.02250, 'b2' => 1.03500, 'c2' => 1.04000, 'd2' => 1.04500, 'e2' => 1.05000, 'f2' => 1.05500, 'g2' => 1.06000, 'h2' => 0.04500,
        'a1' => 0.02500, 'b1' => 0.02750, 'c1' => 0.03000, 'd1' => 0.03250, 'e1' => 0.03500, 'f1' => 0.03750, 'g1' => 0.04000, 'h1' => 0.04250,
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
