<?php

namespace Chess\Eval;

use Chess\Eval\PressureEval;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Relative Pin Evaluation
 *
 * A tactic that occurs when a piece is shielding a more valuable piece, so if
 * it moves out of the line of attack the more valuable piece can be captured
 * resulting in a material gain.
 */
class RelativePinEval extends AbstractEval implements
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

        $pressureEval = (new PressureEval($this->board))->getResult();

        foreach ($this->board->pieces() as $piece) {
            if (
                $piece->id !== Piece::K &&
                $piece->id !== Piece::Q &&
                !$piece->isPinned()
            ) {
                $this->board->detach($piece);
                $this->board->refresh();
                $newPressureEval = (new PressureEval($this->board))->getResult();
                foreach (array_diff($newPressureEval[$piece->oppColor()], $pressureEval[$piece->oppColor()]) as $sq) {
                    foreach ($this->board->pieceBySq($sq)->attacking() as $newAttacking) {
                        foreach ($piece->attacking() as $attacking) {
                            if ($newAttacking->sq === $attacking->sq) {
                                $valDiff = self::$value[$attacking->id] - self::$value[$this->board->pieceBySq($sq)->id];
                                if ($valDiff < 0) {
                                    $this->result[$piece->oppColor()] += abs(round($valDiff, 2));
                                    $this->elaborate($piece);
                                }
                            }
                        }
                    }
                }
                $this->board->attach($piece);
                $this->board->refresh();
            }
        }

        $this->explain($this->result);
    }

    /**
     * Elaborate on the evaluation.
     *
     * @param \Chess\Variant\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->elaboration[] = ucfirst("$phrase is pinned shielding a piece that is more valuable than the attacking piece.");
    }
}
