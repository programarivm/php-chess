<?php

namespace Chess\Eval;

use Chess\Piece\Classical\P;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class AdvancedPawnEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Advanced pawn';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->range = [1, 4];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight advanced pawn advantage",
            "has a moderate advanced pawn advantage",
            "has a decisive advanced pawn advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P && $this->isAdvanced($piece)) {
                $this->result[$piece->color][] = $piece->sq;
            }
        }

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);

        $this->elaborate($this->result);
    }

    private function isAdvanced(P $pawn): bool
    {
        if ($pawn->color === Color::W) {
            if ($pawn->rank() >= 5) {
                return true;
            }
        } else {
            if ($pawn->rank() <= 4) {
                return true;
            }
        }

        return false;
    }

    private function elaborate(array $result): void
    {
        $singular = mb_strtolower('an ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
