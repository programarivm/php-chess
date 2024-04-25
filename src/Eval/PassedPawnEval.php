<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * PassedPawnEval
 *
 * A pawn is passed if it has no opposing pawns on either the same file or
 * adjacent files.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class PassedPawnEval extends AbstractEval implements DiscreteEvalInterface
{
    const NAME = 'Passed pawn';

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
            if ($piece->getId() === Piece::P && $this->isPassedPawn($piece)) {
                $this->result[$piece->getColor()][] = $piece->getSq();
            }
        }

        $this->elaborate($this->result);
    }

    /**
     * Finds out if a pawn is passed.
     *
     * @param \Chess\Piece\P $pawn
     * @return bool
     */
    private function isPassedPawn(P $pawn): bool
    {
        $leftFile = chr(ord($pawn->getSqFile()) - 1);
        $rightFile = chr(ord($pawn->getSqFile()) + 1);

        foreach ([$leftFile, $pawn->getSqFile(), $rightFile] as $file) {
            if ($pawn->getColor() === Color::W) {
                for ($i = $pawn->getSqRank() + 1; $i <= $this->board->getSize()['ranks'] - 1; $i++) {
                    if ($piece = $this->board->getPieceBySq($file.$i)) {
                        if ($piece->getId() === Piece::P && $piece->getColor() !== $pawn->getColor()) {
                            return false;
                        }
                    }
                }
            } else {
                for ($i = $pawn->getSqRank() - 1; $i >= 2; $i--) {
                    if ($piece = $this->board->getPieceBySq($file.$i)) {
                        if ($piece->getId() === Piece::P && $piece->getColor() !== $pawn->getColor()) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Elaborate on the result.
     *
     * @param array $result
     */
    private function elaborate(array $result): void
    {
        $singular = mb_strtolower('a ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
