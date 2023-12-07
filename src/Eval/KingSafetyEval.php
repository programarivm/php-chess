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
 * @license GPL
 */
class KingSafetyEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'King safety';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $pressEval = (new PressureEval($this->board))->getResult();
        $spEval = (new SpaceEval($this->board))->getResult();

        $this->color(Color::W, $pressEval, $spEval);
        $this->color(Color::B, $pressEval, $spEval);
    }

    private function color(string $color, array $pressEval, array $spEval): void
    {
        $king = $this->board->getPiece($color, Piece::K);
        foreach ($king->getMobility() as $key => $sq) {
            if ($piece = $this->board->getPieceBySq($sq)) {
                if ($piece->getColor() === $king->oppColor()) {
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
