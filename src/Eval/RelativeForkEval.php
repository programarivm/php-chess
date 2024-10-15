<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Relative Fork Evaluation
 *
 * A fork is a tactic in which a piece attacks multiple pieces at the same time.
 * It is a double attack. A fork not involving the enemy king is a relative
 * fork.
 */
class RelativeForkEval extends AbstractEval implements
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
    const NAME = 'Relative fork';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 9];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight relative fork advantage",
            "has a moderate relative fork advantage",
            "has a total relative fork advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if (!$piece->isAttackingKing()) {
                $attacked = $piece->attacked();
                if (count($attacked) >= 2) {
                    $pieceValue = self::$value[$piece->id];
                    foreach ($attacked as $attacked) {
                        $attackedValue = self::$value[$attacked->id];
                        if ($pieceValue < $attackedValue) {
                            $this->result[$piece->color] += $attackedValue;
                            $this->elaborate($attacked);
                        }
                    }
                }
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

        $this->elaboration[] = "Relative fork attack on {$phrase}.";
    }
}
