<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Piece\P;

/**
 * Advanced Pawn Evaluation
 *
 * A pawn that is on the fifth rank or higher.
 */
class AdvancedPawnEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
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
                $this->elaborate($piece);
            }
        }

        $this->reelaborate('These are advanced pawns: ', $ucfirst = false);

        $this->explain([
            Color::W => count($this->result[Color::W]),
            Color::B => count($this->result[Color::B]),
        ]);
    }

    /**
     * Returns true if the pawn is advanced.
     *
     * @param \Chess\Variant\Classical\Piece\P $pawn
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
}
