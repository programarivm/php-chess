<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Protection Evaluation
 *
 * If a piece is unprotected, it means that there are no other pieces defending
 * it, and therefore it could be taken for free resulting in a material gain.
 * This indicates a forcing move in that a player is to reply in a certain way.
 * On the next turn, it should be defended by a piece or moved to a safe square.
 * The player with the greater number of material points under protection has an
 * advantage.
 *
 * @see \Chess\Eval\AttackEval
 */
class ProtectionEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Protection';

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
            "has better protected pieces",
        ];

        foreach ($this->board->pieces() as $piece) {
            foreach ($piece->attacked() as $attacked) {
                if ($attacked->id !== Piece::K) {
                    if (!$attacked->defending()) {
                        $this->result[$attacked->oppColor()] += self::$value[$attacked->id];
                        $this->toElaborate[] = $attacked;
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
            $phrase = ucfirst(PiecePhrase::create($val)) . " is unprotected.";
            if (!in_array($phrase, $this->elaboration)) {
                $this->elaboration[] = $phrase;
            }
        }

        return $this->elaboration;
    }
}
