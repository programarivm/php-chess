<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Absolute Fork Evaluation
 *
 * A fork is a tactic in which a piece attacks multiple enemy pieces
 * simultaneously. A fork is a type of double attack. If the king is one of the
 * attacked pieces, the term absolute fork is then used.
 */
class AbsoluteForkEval extends AbstractEval implements ElaborateEvalInterface
{
    use ElaborateEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Absolute fork';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        foreach ($this->board->pieces() as $piece) {
            if ($piece->isAttackingKing()) {
                foreach ($piece->attacked() as $attacked) {
                    if ($attacked->id !== Piece::K) {
                        if (self::$value[$piece->id] < self::$value[$attacked->id]) {
                            $this->result[$piece->color] += self::$value[$attacked->id];
                            $this->elaborate($attacked);
                        }
                    }
                }
            }
        }
    }

    /**
     * Elaborate on the evaluation.
     *
     * @param \Chess\Variant\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->elaboration[] = "Absolute fork attack on {$phrase}.";
    }
}
