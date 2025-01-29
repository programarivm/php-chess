<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Relative Pin Evaluation
 *
 * A tactic that occurs when a piece is shielding a more valuable piece, so if
 * it moves out of the line of attack the more valuable piece can be captured
 * resulting in a material gain.
 */
class RelativePinEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Relative pin';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a relative pin advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            foreach ($piece->attacking() as $attacking) {
                if (is_a($attacking, AbstractLinePiece::class)) {
                    foreach ($this->board->pieces($piece->color) as $val) {
                        if ($val->id !== Piece::K &&
                            $piece->isBetween($attacking, $val) &&
                            $piece->isEmpty($piece->line($val->sq))
                        ) {
                            $diff = self::$value[$attacking->id] - self::$value[$val->id];
                            if ($diff < 0) {
                                $this->result[$piece->oppColor()] += abs(round($diff, 4));
                                $this->toElaborate[] = $piece;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Elaborate on the evaluation.
     *
     * @return array
     */
    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $phrase = PiecePhrase::create($val);
            $this->elaboration[] = ucfirst("$phrase is pinned shielding a piece that is more valuable than the attacking piece.");
        }

        return $this->elaboration;
    }
}
