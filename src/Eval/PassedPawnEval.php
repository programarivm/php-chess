<?php

namespace Chess\Eval;

use Chess\Piece\Classical\P;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class PassedPawnEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Passed pawn';

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
            "has a slight passed pawn advantage",
            "has a moderate passed pawn advantage",
            "has a decisive passed pawn advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P && $this->isPassed($piece)) {
                $this->result[$piece->color][] = $piece->sq;
            }
        }

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);

        $this->elaborate($this->result);
    }

    private function isPassed(P $pawn): bool
    {
        $leftFile = chr(ord($pawn->file()) - 1);
        $rightFile = chr(ord($pawn->file()) + 1);

        foreach ([$leftFile, $pawn->file(), $rightFile] as $file) {
            if ($pawn->color === Color::W) {
                for ($i = $pawn->rank() + 1; $i <= $this->board->square::SIZE['ranks'] - 1; $i++) {
                    if ($piece = $this->board->pieceBySq($file.$i)) {
                        if ($piece->id === Piece::P && $piece->color !== $pawn->color) {
                            return false;
                        }
                    }
                }
            } else {
                for ($i = $pawn->rank() - 1; $i >= 2; $i--) {
                    if ($piece = $this->board->pieceBySq($file.$i)) {
                        if ($piece->id === Piece::P && $piece->color !== $pawn->color) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    private function elaborate(array $result): void
    {
        $singular = mb_strtolower('a ' . self::NAME);
        $plural = mb_strtolower(self::NAME.'s');

        $this->shorten($result, $singular, $plural);
    }
}
