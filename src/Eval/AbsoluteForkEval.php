<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Absolute Fork Evaluation
 *
 * A tactic in which a piece attacks multiple pieces at the same time. It is a
 * double attack. A fork involving the enemy king is an absolute fork.
 */
class AbsoluteForkEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

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

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has an absolute fork advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->isAttackingKing()) {
                foreach ($piece->attacked() as $attacked) {
                    if ($attacked->id !== Piece::K) {
                        if (self::$value[$piece->id] < self::$value[$attacked->id]) {
                            $this->result[$piece->color] += self::$value[$attacked->id];
                            $this->toElaborate[] = [
                                $piece,
                                $attacked,
                            ];
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
             $this->elaboration[] = ucfirst(PiecePhrase::create($val[0])) .
                " is attacking both " .
                PiecePhrase::create($val[1]) .
                " and the opponent's king at the same time.";
        }

        return $this->elaboration;
    }
}
