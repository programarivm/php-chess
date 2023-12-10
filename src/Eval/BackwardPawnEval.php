<?php

namespace Chess\Eval;

use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Backward P
 *
 * @author Boas Falke
 * @license GPL
 */
class BackwardPawnEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'Backward pawn';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $sqs = [];
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                $left = chr(ord($piece->getSq()) - 1);
                $right = chr(ord($piece->getSq()) + 1);
                if (
                    !$this->isDefensible($piece, $left) &&
                    !$this->isDefensible($piece, $right)
                ) {
                    $this->result[$piece->getColor()] += 1;
                    $sqs[] = $piece->getSq();
                }
            }
        }
    }

    private function isDefensible($pawn, $file): bool
    {
        $rank = (int) $pawn->getSqRank();
        for ($i = 1; $i <= 8; $i++) {
            if ($piece = $this->board->getPieceBySq($file.$i)) {
                if (
                    $piece->getId() === Piece::P &&
                    $piece->getColor() === $pawn->getColor()
                ) {
                    if ($piece->getColor() === Color::W) {
                        if ($i <= $rank - 1) {
                            return true;
                        }
                    } else {
                        if ($i >= $rank + 1) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
