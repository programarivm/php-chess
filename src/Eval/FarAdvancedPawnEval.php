<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Far Advanced Pawn Evaluation
 *
 * A pawn that is threatening to promote.
 */
class FarAdvancedPawnEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Far-advanced pawn';

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
            "has a far advanced pawn advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P && $this->isFarAdvanced($piece)) {
                $this->result[$piece->color][] = $piece->sq;
                $this->toElaborate[] = $piece;
            }
        }
    }

    /**
     * Returns true if the pawn is far advanced.
     *
     * @param \Chess\Variant\Classical\P $pawn
     * @return bool
     */
    private function isFarAdvanced(P $pawn): bool
    {
        if ($pawn->color === Color::W) {
            if ($pawn->rank() >= 6) {
                return true;
            }
        } else {
            if ($pawn->rank() <= 3) {
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

        $this->shorten('These pawns are threatening to promote: ', $ucfirst = false);

        return $this->elaboration;
    }
}
