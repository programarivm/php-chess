<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Isolated Pawn Evaluation
 *
 * A pawn without friendly pawns on the adjacent files. Since it cannot be
 * defended by other pawns it is considered a weakness.
 */
class IsolatedPawnEval extends AbstractEval implements InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Isolated pawn';

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
            "has more isolated pawns against than its opponent",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P) {
                if ($this->isIsolated($piece)) {
                    $this->result[$piece->color][] = $piece->sq;
                    $this->toElaborate[] = $piece;
                }
            }
        }
    }

    /**
     * Returns true if the given pawn is isolated.
     *
     * @param \Chess\Variant\Classical\P $pawn
     * @return bool
     */
    private function isIsolated(P $pawn): bool
    {
        $left = chr(ord($pawn->sq) - 1);
        $right = chr(ord($pawn->sq) + 1);
        for ($i = 2; $i < $this->board->square::SIZE['ranks']; $i++) {
            if ($piece = $this->board->pieceBySq($left . $i)) {
                if ($piece->id === Piece::P && $piece->color === $pawn->color) {
                    return false;
                }
            }
            if ($piece = $this->board->pieceBySq($right . $i)) {
                if ($piece->id === Piece::P && $piece->color === $pawn->color) {
                    return false;
                }
            }
        }

        return true;
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

        $this->shorten('The following are isolated pawns: ', $ucfirst = false);

        return $this->elaboration;
    }
}
