<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\P;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Passed Pawn Evaluation
 *
 * A pawn with no opposing pawns on either the same file or adjacent files to
 * prevent it from being promoted.
 */
class PassedPawnEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Passed pawn';

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
            "has more passed pawns in its favor",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P && $this->isPassed($piece)) {
                $this->result[$piece->color][] = $piece->sq;
                $this->toElaborate[] = $piece;
            }
        }
    }
    /**
     * Returns true if the given pawn is passed.
     *
     * @param \Chess\Variant\Classical\P $pawn
     * @return bool
     */
    private function isPassed(P $pawn): bool
    {
        $leftFile = chr(ord($pawn->file()) - 1);
        $rightFile = chr(ord($pawn->file()) + 1);

        foreach ([$leftFile, $pawn->file(), $rightFile] as $file) {
            if ($pawn->color === Color::W) {
                for ($i = $pawn->rank() + 1; $i <= $this->board->square::SIZE['ranks'] - 1; $i++) {
                    if ($piece = $this->board->pieceBySq($file . $i)) {
                        if ($piece->id === Piece::P && $piece->color !== $pawn->color) {
                            return false;
                        }
                    }
                }
            } else {
                for ($i = $pawn->rank() - 1; $i >= 2; $i--) {
                    if ($piece = $this->board->pieceBySq($file . $i)) {
                        if ($piece->id === Piece::P && $piece->color !== $pawn->color) {
                            return false;
                        }
                    }
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

        $this->shorten('The following are passed pawns: ', $ucfirst = false);

        return $this->elaboration;
    }
}
