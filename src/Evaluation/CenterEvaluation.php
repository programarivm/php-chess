<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\Evaluation\SpaceEvaluation;
use Chess\PGN\Symbol;

/**
 * Center.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class CenterEvaluation extends AbstractEvaluation
{
    const NAME = 'center';

    private $center = [
        'a8' => 0, 'b8' => 0, 'c8' => 0, 'd8' => 0, 'e8' => 0, 'f8' => 0, 'g8' => 0, 'h8' => 0,
        'a7' => 0, 'b7' => 1, 'c7' => 1, 'd7' => 1, 'e7' => 1, 'f7' => 1, 'g7' => 1, 'h7' => 0,
        'a6' => 0, 'b6' => 1, 'c6' => 2, 'd6' => 2, 'e6' => 2, 'f6' => 2, 'g6' => 1, 'h6' => 0,
        'a5' => 0, 'b5' => 1, 'c5' => 2, 'd5' => 3, 'e5' => 3, 'f5' => 2, 'g5' => 1, 'h5' => 0,
        'a4' => 0, 'b4' => 1, 'c4' => 2, 'd4' => 3, 'e4' => 3, 'f4' => 2, 'g4' => 1, 'h4' => 0,
        'a3' => 0, 'b3' => 1, 'c3' => 2, 'd3' => 2, 'e3' => 2, 'f3' => 2, 'g3' => 1, 'h3' => 0,
        'a2' => 0, 'b2' => 1, 'c2' => 1, 'd2' => 1, 'e2' => 1, 'f2' => 1, 'g2' => 1, 'h2' => 0,
        'a1' => 0, 'b1' => 0, 'c1' => 0, 'd1' => 0, 'e1' => 0, 'f1' => 0, 'g1' => 0, 'h1' => 0,
    ];

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate(): array
    {
        $spEvald = (new SpaceEvaluation($this->board))->evaluate();
        foreach ($this->center as $sq => $val) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                $this->result[$piece->getColor()] += $this->value[$piece->getId()] * $val;
            }
            if (in_array($sq, $spEvald[Symbol::WHITE])) {
                $this->result[Symbol::WHITE] += $val;
            }
            if (in_array($sq, $spEvald[Symbol::BLACK])) {
                $this->result[Symbol::BLACK] += $val;
            }
        }
        $this->result[Symbol::WHITE] = round($this->result[Symbol::WHITE], 2);
        $this->result[Symbol::BLACK] = round($this->result[Symbol::BLACK], 2);

        return $this->result;
    }
}
