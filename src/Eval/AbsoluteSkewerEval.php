<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Absolute Skewer Evaluation
 *
 * A tactic in which the enemy king is involved. The king is in check, and it
 * has to move out of danger exposing a more valuable piece to capture. Only
 * line pieces (bishops, rooks and queens) can skewer.
 */
class AbsoluteSkewerEval extends AbstractEval
{
    use ElaborateEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Absolute skewer';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        foreach ($this->board->pieces() as $piece) {
            if (is_a($piece, AbstractLinePiece::class) && $piece->isAttackingKing()) {
                $king = $this->board->piece($this->board->turn, Piece::K);
                foreach ($king->defending() as $defending) {
                    if ($defending->isAlignedWith($king, $piece)) {
                        if (self::$value[$piece->id] < self::$value[$defending->id]) {
                            $this->result[$piece->color] = 1;
                            $this->toElaborate[] = [
                                $piece,
                                $king,
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
            $attacking = PiecePhrase::create($val[0]);
            $attacked = PiecePhrase::create($val[1]);
            $this->elaboration[] = "When $attacked will be moved, a piece that is more valuable than $attacking may well be exposed to attack.";
        }

        return $this->elaboration;
    }
}
