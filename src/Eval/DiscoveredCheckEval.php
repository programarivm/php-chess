<?php

namespace Chess\Eval;

use Chess\Tutor\ColorPhrase;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Discovered Check Evaluation
 *
 * Evaluates the advantage gained as a result of the existence of discovered
 * checks. A discovered check occurs when the opponent's king can be checked by
 * moving a piece out of the way of another.
 */
class DiscoveredCheckEval extends AbstractEval implements
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
    const NAME = 'Discovered check';

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
            "has a slight discovered check advantage",
            "has a moderate discovered check advantage",
            "has a total discovered check advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id !== Piece::K) {
                $king = $this->board->piece($piece->oppColor(), Piece::K);
                $clone = $this->board->clone();
                $clone->detach($clone->pieceBySq($piece->sq));
                $clone->refresh();
                $newKing = $clone->piece($piece->oppColor(), Piece::K);
                foreach ($this->board->diffPieces($king->attacking(), $newKing->attacking()) as $diffPiece) {
                    if ($diffPiece->color === $piece->color) {
                        $this->result[$piece->color] += self::$value[$piece->id];
                        $this->elaborate($piece);
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
        $sentence = ColorPhrase::sentence($piece->oppColor());

        $this->elaboration[] = ucfirst("The $sentence king can be put in check as long as $phrase moves out of the way.");
    }
}
