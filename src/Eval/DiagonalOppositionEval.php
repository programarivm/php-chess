<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Diagonal Opposition Evaluation
 *
 * The same as direct opposition, but the two kings are apart from each other
 * diagonally.
 */
class DiagonalOppositionEval extends AbstractEval
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Diagonal opposition';

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
            "has the diagonal opposition preventing the advance of the other king",
        ];

        $intersect = array_intersect(
            $this->board->piece(Color::W, Piece::K)->mobility,
            $this->board->piece(Color::B, Piece::K)->mobility
        );

        if (count($intersect) === 1) {
            $this->result = [
                Color::W => (int) ($this->board->turn !== Color::W),
                Color::B => (int) ($this->board->turn !== Color::B),
            ];
        }
    }
}
