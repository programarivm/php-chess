<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

/**
 * BadBishopEval
 *
 * A bad bishop is a bishop that is blocked by its own pawns.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class BadBishopEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'Bad bishop';

    /**
     * Returns the result.
     *
     * @return array
     */
    public function eval(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::B) {
                $this->result[$piece->getColor()] += $this->count(
                    $piece->getColor(),
                    Square::color($piece->getSq())
                );
            }
        }

        return $this->result;
    }

    /**
     * Counts the number of pawns blocking the bishop.
     *
     * @param string $bColor
     * @param string $sqColor
     * @return int
     */
    private function count(string $bColor, string $sqColor): int
    {
        $count = 0;
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                if (
                    $piece->getColor() === $bColor &&
                    Square::color($piece->getSq()) === $sqColor
                ) {
                    $count += 1;
                }
            }
        }

        return $count;
    }
}
