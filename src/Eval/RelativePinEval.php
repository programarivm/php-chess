<?php

namespace Chess\Eval;

use Chess\Eval\PressureEval;
use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
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

        $before = (new PressureEval($this->board))->result;

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id !== Piece::K && $piece->id !== Piece::Q) {
                $this->board->detach($piece);
                $this->board->refresh();
                if (!$this->board->isCheck()) {
                    $after = (new PressureEval($this->board))->result;
                    $diff = array_diff($after[$piece->oppColor()], $before[$piece->oppColor()]);
                    foreach ($diff as $sq) {
                        foreach ($this->board->pieceBySq($sq)->attacking() as $attacking) {
                            $valDiff = self::$value[$attacking->id] -
                                self::$value[$this->board->pieceBySq($sq)->id];
                            if ($valDiff < 0) {
                                $this->result[$piece->oppColor()] += abs(round($valDiff, 4));
                                $this->toElaborate[] = $piece;
                            }
                        }
                    }
                }
                $this->board->attach($piece);
                $this->board->refresh();
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
