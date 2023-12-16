<?php

namespace Chess\Eval;

use Chess\Eval\BishopPairEval;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
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
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $bishopPairEval = (new BishopPairEval($board))->getResult();

        if (!$bishopPairEval[Color::W] && !$bishopPairEval[Color::B]) {
            foreach ($this->board->getPieces() as $piece) {
                if ($piece->getId() === Piece::B) {
                    $this->result[$piece->getColor()] += $this->countBlockingPawns(
                        $piece,
                        Square::color($piece->getSq())
                    );
                }
            }
        }
    }

    /**
     * Counts the number of pawns blocking a bishop.
     *
     * @param \Chess\Piece\AbstractPiece $bishop
     * @param string $sqColor
     * @return int
     */
    private function countBlockingPawns($bishop, string $sqColor): int
    {
        $count = 0;
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                if (
                    $piece->getColor() === $bishop->getColor() &&
                    Square::color($piece->getSq()) === $sqColor
                ) {
                    $count += 1;
                }
            }
        }

        return $count;
    }
}
