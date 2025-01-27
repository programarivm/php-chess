<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;

/**
 * Absolute Pin Evaluation
 *
 * A tactic that occurs when a piece is shielding the king, so it cannot move
 * out of the line of attack because the king would be put in check.
 */
class AbsolutePinEval extends AbstractEval implements InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Absolute pin';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'Black',
            'White',
        ];

        $this->observation = [
            "has an absolute pin advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($pinning = $piece->isPinned()) {
                $this->result[$piece->color] += self::$value[$piece->id];
                $this->toElaborate[] = [
                    $piece,
                    $pinning,
                ];
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
            $pinned = PiecePhrase::create($val[0]);
            $pinning = PiecePhrase::create($val[1]);
            $this->elaboration[] = ucfirst("$pinned is pinned shielding the king so it cannot move out of the line of attack of $pinning because the king would be put in check.");
        }

        return $this->elaboration;
    }
}
