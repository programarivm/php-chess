<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class IsolatedPawnEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface,
    InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Isolated pawn';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->range = [1, 4];

        $this->subject = [
            'Black',
            'White',
        ];

        $this->observation = [
            "has a slight isolated pawn advantage",
            "has a moderate isolated pawn advantage",
            "has a decisive isolated pawn advantage",
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->id === Piece::P) {
                if ($this->isIsolatedPawn($piece)) {
                    $this->result[$piece->color][] = $piece->sq;
                }
            }
        }

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);

        $this->elaborate($this->result);
    }

    private function isIsolatedPawn(P $pawn): int
    {
        $left = chr(ord($pawn->sq) - 1);
        $right = chr(ord($pawn->sq) + 1);
        for ($i = 2; $i < $this->board->square::SIZE['ranks']; $i++) {
            if ($piece = $this->board->getPieceBySq($left.$i)) {
                if ($piece->id === Piece::P && $piece->color === $pawn->color) {
                    return false;
                }
            }
            if ($piece = $this->board->getPieceBySq($right.$i)) {
                if ($piece->id === Piece::P && $piece->color === $pawn->color) {
                    return false;
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
        $singular = mb_strtolower('an ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
