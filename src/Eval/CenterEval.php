<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Center Evaluation
 *
 * It is advantageous to control the central squares as well as to place a piece
 * in the center.
 */
class CenterEval extends AbstractEval
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Center';

    /**
     * The equilibrium factor φ.
     *
     * The king's side is slightly tilted to differentiate between moves made on
     * the queen side and those made on the king side.
     *
     * @var string
     */
    const PHI = 0.1;

    /**
     * Integer values are assigned to squares based on their proximity to the
     * center. The closer a square is to the center, the higher its value.
     *
     * @var array
     */
    private array $center = [
        'a8' => 0, 'b8' => 0, 'c8' => 0, 'd8' => 0, 'e8' => 0, 'f8' => 0, 'g8' => 0, 'h8' => 0,
        'a7' => 0, 'b7' => 1, 'c7' => 1, 'd7' => 1, 'e7' => 1 + self::PHI, 'f7' => 1 + self::PHI, 'g7' => 1 + self::PHI, 'h7' => 0,
        'a6' => 0, 'b6' => 1, 'c6' => 2, 'd6' => 2, 'e6' => 2 + self::PHI, 'f6' => 2 + self::PHI, 'g6' => 1 + self::PHI, 'h6' => 0,
        'a5' => 0, 'b5' => 1, 'c5' => 2, 'd5' => 3, 'e5' => 3 + self::PHI, 'f5' => 2 + self::PHI, 'g5' => 1 + self::PHI, 'h5' => 0,
        'a4' => 0, 'b4' => 1, 'c4' => 2, 'd4' => 3, 'e4' => 3 + self::PHI, 'f4' => 2 + self::PHI, 'g4' => 1 + self::PHI, 'h4' => 0,
        'a3' => 0, 'b3' => 1, 'c3' => 2, 'd3' => 2, 'e3' => 2 + self::PHI, 'f3' => 2 + self::PHI, 'g3' => 1 + self::PHI, 'h3' => 0,
        'a2' => 0, 'b2' => 1, 'c2' => 1, 'd2' => 1, 'e2' => 1 + self::PHI, 'f2' => 1 + self::PHI, 'g2' => 1 + self::PHI, 'h2' => 0,
        'a1' => 0, 'b1' => 0, 'c1' => 0, 'd1' => 0, 'e1' => 0, 'f1' => 0, 'g1' => 0, 'h1' => 0,
    ];

    /**
     * @param \Chess\Variant\AbstractBoard $board
     *
     * If a piece occupies a square, its value is considered in the total sum of
     * the result. The more valuable the piece, the better. To this sum are also
     * added the squares controlled by each player. The controlled squares are
     * those that are in each player's space.
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
                $this->result[$piece->color] += self::$value[$piece->id] * $val;
            }
            if (in_array($sq, $this->board->spaceEval[Color::W])) {
                $this->result[Color::W] += $val;
            }
            if (in_array($sq, $this->board->spaceEval[Color::B])) {
                $this->result[Color::B] += $val;
            }
        }

        $this->result[Color::W] = round($this->result[Color::W], 2);
        $this->result[Color::B] = round($this->result[Color::B], 2);
    }
}
