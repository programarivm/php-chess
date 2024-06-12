<?php

namespace Chess\Eval;

use Chess\Eval\IsolatedPawnEval;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class BackwardPawnEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface,
    InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Backward pawn';

    private array $isolatedPawnEval;

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
            "has a slight backward pawn advantage",
            "has a moderate backward pawn advantage",
            "has a decisive backward pawn advantage",
        ];

        $this->isolatedPawnEval = (new IsolatedPawnEval($board))->getResult();

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->id === Piece::P) {
                $left = chr(ord($piece->sq) - 1);
                $right = chr(ord($piece->sq) + 1);
                if (
                    !$this->isDefensible($piece, $left) &&
                    !$this->isDefensible($piece, $right) &&
                    !in_array($piece->sq, [
                        ...$this->isolatedPawnEval[Color::W],
                        ...$this->isolatedPawnEval[Color::B]
                    ])
                ) {
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

    private function isDefensible(AbstractPiece $pawn, string $file): bool
    {
        if ($pawn->rank() == 2 || $pawn->rank() == $this->board->square::SIZE['ranks'] - 1) {
            return true;
        }

        if ($pawn->color === Color::W) {
            for ($i = $pawn->rank() - 1; $i >= 2; $i--) {
                if ($piece = $this->board->getPieceBySq($file.$i)) {
                    if ($piece->id === Piece::P && $piece->color === $pawn->color) {
                        return true;
                    }
                }
            }
        } else {
            for ($i = $pawn->rank() + 1; $i <= $this->board->square::SIZE['ranks'] - 1; $i++) {
                if ($piece = $this->board->getPieceBySq($file.$i)) {
                    if ($piece->id === Piece::P && $piece->color === $pawn->color
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
