<?php

namespace Chess\Eval;

use Chess\Eval\IsolatedPawnEval;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class BackwardPawnEval extends AbstractEval implements DiscreteEvalInterface, InverseEvalInterface
{
    const NAME = 'Backward pawn';

    private array $isolatedPawnEval;

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->isolatedPawnEval = (new IsolatedPawnEval($board))->getResult();

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() === Piece::P) {
                $left = chr(ord($piece->getSq()) - 1);
                $right = chr(ord($piece->getSq()) + 1);
                if (
                    !$this->isDefensible($piece, $left) &&
                    !$this->isDefensible($piece, $right) &&
                    !in_array($piece->getSq(), [
                        ...$this->isolatedPawnEval[Color::W],
                        ...$this->isolatedPawnEval[Color::B]
                    ])
                ) {
                    $this->result[$piece->getColor()][] = $piece->getSq();
                }
            }
        }

        $this->elaborate($this->result);
    }

    private function isDefensible(AbstractPiece $pawn, string $file): bool
    {
        if ($pawn->getSqRank() == 2 || $pawn->getSqRank() == $this->board->getSize()['ranks'] - 1) {
            return true;
        }

        if ($pawn->getColor() === Color::W) {
            for ($i = $pawn->getSqRank() - 1; $i >= 2; $i--) {
                if ($piece = $this->board->getPieceBySq($file.$i)) {
                    if ($piece->getId() === Piece::P && $piece->getColor() === $pawn->getColor()) {
                        return true;
                    }
                }
            }
        } else {
            for ($i = $pawn->getSqRank() + 1; $i <= $this->board->getSize()['ranks'] - 1; $i++) {
                if ($piece = $this->board->getPieceBySq($file.$i)) {
                    if ($piece->getId() === Piece::P && $piece->getColor() === $pawn->getColor()
                    ) {
                        return true;
                    }
                }
            }
        }

        return false;
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
