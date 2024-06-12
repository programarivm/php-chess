<?php

namespace Chess\Eval;

use Chess\Eval\PressureEval;
use Chess\Eval\SpaceEval;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * K safety.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class KingSafetyEval extends AbstractEval implements
    ExplainEvalInterface,
    InverseEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'King safety';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1, 4];

        $this->subject =  [
            'The black pieces',
            'The white pieces',
        ];

        $this->observation = [
            "are timidly approaching the other side's king",
            "are approaching the other side's king",
            "are getting worryingly close to the adversary's king",
            "are more than desperately close to the adversary's king",
        ];

        $pressEval = (new PressureEval($this->board))->getResult();
        $spEval = (new SpaceEval($this->board))->getResult();

        $this->color(Color::W, $pressEval, $spEval);
        $this->color(Color::B, $pressEval, $spEval);

        $this->explain($this->result);
    }

    private function color(string $color, array $pressEval, array $spEval): void
    {
        $king = $this->board->getPiece($color, Piece::K);
        foreach ($king->mobility as $sq) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                if ($piece->color === $king->oppColor()) {
                    $this->result[$color] += 1;
                }
            }
            if (in_array($sq, $pressEval[$king->oppColor()])) {
                $this->result[$color] += 1;
            }
            if (in_array($sq, $spEval[$king->oppColor()])) {
                $this->result[$color] += 1;
            }
        }
    }
}
