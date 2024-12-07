<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

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
class ProtectionEval extends AbstractEval implements
    ExplainEvalInterface
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

        $this->range = [1, 4];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight protection advantage",
            "has a moderate protection advantage",
            "has a decisive protection advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            foreach ($piece->attacked() as $attacked) {
                if ($attacked->id !== Piece::K) {
                    if (!$attacked->defending()) {
                        $this->result[$attacked->oppColor()] += self::$value[$attacked->id];
                        $this->elaborate($attacked);
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
        $phrase = ucfirst("$phrase is unprotected.");
        if (!in_array($phrase, $this->elaboration)) {
            $this->elaboration[] = $phrase;
        }
    }
}
