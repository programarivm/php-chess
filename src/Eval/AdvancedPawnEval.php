<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Advanced Pawn Evaluation
 *
 * A pawn that is on the fifth rank or higher.
 */
class AdvancedPawnEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Advanced pawn';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->result = [
            Color::W => [],
            Color::B => [],
        ];

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has more advanced pawns in its favor",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P && $this->isAdvanced($piece)) {
                $this->result[$piece->color][] = $piece->sq;
                $this->toElaborate[] = $piece;
            }
        }
    }

    /**
     * Returns true if the pawn is advanced.
     *
     * @param \Chess\Variant\Classical\P $pawn
     * @return bool
     */
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

    /**
     * Elaborate on the evaluation.
     *
     * @return array
     */
    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $this->elaboration[] = $val->sq;
        }

        $this->shorten('These are advanced pawns: ', $ucfirst = false);

        return $this->elaboration;
    }
}
