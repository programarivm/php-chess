<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * AdvancedPawnEval
 *
 * A pawn is advanced if it is on the opponent's side of the board
 * (the fifth rank or higher).
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class AdvancedPawnEval extends AbstractEval
{
    const NAME = 'Advanced pawn';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P && $this->isAdvancedPawn($piece)) {
                $this->result[$piece->getColor()][] = $piece->getSq();
            }
        }

        $this->explain($this->result);
    }

    /**
     * Finds out if a pawn is advanced.
     *
     * @param \Chess\Piece\P $pawn
     * @return bool
     */
    private function isAdvancedPawn(P $pawn): bool
    {
        $rank = (int) $pawn->getSqRank();
        if ($pawn->getColor() === Color::W) {
            if ($rank >= 5) {
                return true;
            }
        } else {
            if ($rank <= 4) {
                return true;
            }
        }

        return false;
    }

    /**
     * Explain the result.
     *
     * @param array $result
     */
    private function explain(array $result): void
    {
        $singular = mb_strtolower('an ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
