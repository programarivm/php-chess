<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Relative Fork Evaluation
 *
 * A tactic in which a piece attacks multiple pieces at the same time. It is a
 * double attack. A fork not involving the enemy king is a relative fork.
 */
class RelativeForkEval extends AbstractEval
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

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a relative fork advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if (!$piece->isAttackingKing()) {
                $attacked = $piece->attacked();
                if (count($attacked) >= 2) {
                    $pieceValue = self::$value[$piece->id];
                    foreach ($attacked as $val) {
                        $attackedValue = self::$value[$val->id];
                        if ($pieceValue < $attackedValue) {
                            $this->result[$piece->color] += $attackedValue;
                            $this->toElaborate[] = [
                                $piece,
                                $val,
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
            $this->elaboration[] = ucfirst(PiecePhrase::create($val[1])) . 
                " is under a fork attack by " . 
                PiecePhrase::create($val[0]) .
                ".";
        }

        return $this->elaboration;
    }
}
