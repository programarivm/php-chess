<?php

namespace Chess\Eval;

use Chess\Eval\SpaceEval;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * CenterEval class
 * 
 * This class implements a heuristic to evaluate the control of the center of the chessboard.
 * The heuristic assigns values to squares based on their proximity to the center and
 * considers both piece placement and space control.
 */
class CenterEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Center';

    /**
     * Center control values for each square on the board.
     * Central squares have higher values, while edge squares have zero value.
     */
    private array $center = [
        'a8' => 0, 'b8' => 0, 'c8' => 0, 'd8' => 0, 'e8' => 0, 'f8' => 0, 'g8' => 0, 'h8' => 0,
        'a7' => 0, 'b7' => 1, 'c7' => 1, 'd7' => 1, 'e7' => 1, 'f7' => 1, 'g7' => 1, 'h7' => 0,
        'a6' => 0, 'b6' => 1, 'c6' => 2, 'd6' => 2, 'e6' => 2, 'f6' => 2, 'g6' => 1, 'h6' => 0,
        'a5' => 0, 'b5' => 1, 'c5' => 2, 'd5' => 3, 'e5' => 3, 'f5' => 2, 'g5' => 1, 'h5' => 0,
        'a4' => 0, 'b4' => 1, 'c4' => 2, 'd4' => 3, 'e4' => 3, 'f4' => 2, 'g4' => 1, 'h4' => 0,
        'a3' => 0, 'b3' => 1, 'c3' => 2, 'd3' => 2, 'e3' => 2, 'f3' => 2, 'g3' => 1, 'h3' => 0,
        'a2' => 0, 'b2' => 1, 'c2' => 1, 'd2' => 1, 'e2' => 1, 'f2' => 1, 'g2' => 1, 'h2' => 0,
        'a1' => 0, 'b1' => 0, 'c1' => 0, 'd1' => 0, 'e1' => 0, 'f1' => 0, 'g1' => 0, 'h1' => 0,
    ];

    /**
     * Constructor for CenterEval
     * 
     * Initializes the evaluation by setting up the board, range, subjects, and observations.
     * Calculates the center control score for both players based on piece placement and space control.
     *
     * @param AbstractBoard $board The chess board to evaluate
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        // Set the range for evaluation scores
        $this->range = [1, 9];

        // Define the subjects (players) for evaluation
        $this->subject = [
            'White',
            'Black',
        ];

        // Define observations for different levels of center control
        $this->observation = [
            "has a slightly better control of the center",
            "has a moderate control of the center",
            "is totally controlling the center",
        ];

        // Get space evaluation results
        $spEval = (new SpaceEval($this->board))->getResult();

        // Calculate center control scores for both players
        foreach ($this->center as $sq => $val) {
            // Add score for pieces on central squares
            if ($piece = $this->board->pieceBySq($sq)) {
                $this->result[$piece->color] += self::$value[$piece->id] * $val;
            }
            // Add score for space control in central squares
            if (in_array($sq, $spEval[Color::W])) {
                $this->result[Color::W] += $val;
            }
            if (in_array($sq, $spEval[Color::B])) {
                $this->result[Color::B] += $val;
            }
        }

        // Round the results to two decimal places
        $this->result[Color::W] = round($this->result[Color::W], 2);
        $this->result[Color::B] = round($this->result[Color::B], 2);

        // Generate explanation for the evaluation
        $this->explain($this->result);
    }
}