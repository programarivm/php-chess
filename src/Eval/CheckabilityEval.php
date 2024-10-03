<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Checkability Evaluation
 *
 * Having a king that can be checked is usually considered a disadvantage,
 * and vice versa, it is considered advantageous to have a king that cannot
 * be checked.
 */
class CheckabilityEval extends AbstractEval implements
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
    const NAME = 'Checkability';

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
            "has a checkability advantage",
        ];

        $wKing = $this->board->piece(Color::W, Piece::K);
        $bKing = $this->board->piece(Color::B, Piece::K);

        if ($this->isCheckable($bKing)) {
            $this->result[Color::W] = 1;
            $this->elaborate($bKing);
        }

        if ($this->isCheckable($wKing)) {
            $this->result[Color::B] = 1;
            $this->elaborate($wKing);
        }

        $this->explain($this->result);
    }

    /**
     * Returns true if the king is checkable.
     *
     * @param \Chess\Variant\AbstractPiece $king
     * @return bool
     */
    private function isCheckable(AbstractPiece $king): bool
    {
        foreach ($this->board->pieces($king->oppColor()) as $piece) {
            foreach ($piece->moveSqs() as $sq) {
                $clone = $this->board->clone();
                $clone->turn = $king->oppColor();
                if ($clone->playLan($king->oppColor(), "{$piece->sq}$sq")) {
                    if ($clone->isCheck()) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Elaborate on the evaluation.
     *
     * @param \Chess\Variant\AbstractPiece $king
     */
    private function elaborate(AbstractPiece $king): void
    {
        $phrase = PiecePhrase::create($king);

        $this->elaboration[] = "{$phrase} can be checked so it is vulnerable to forcing moves.";
    }
}
