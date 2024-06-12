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
 * @license MIT
 */
class AdvancedPawnEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

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

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->id === Piece::P && $this->isAdvancedPawn($piece)) {
                $this->result[$piece->color][] = $piece->sq;
            }
        }

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);

        $this->elaborate($this->result);
    }

    /**
     * Finds out if a pawn is advanced.
     *
     * @param \Chess\Piece\P $pawn
     * @return bool
     */
    private function isAdvancedPawn(P $pawn): bool
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
