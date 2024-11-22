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
                $before = $this->board->piece($piece->oppColor(), Piece::K)->attacking();
                $this->board->detach($piece);
                $this->board->refresh();
                $after = $this->board->piece($piece->oppColor(), Piece::K)->attacking();
                foreach ($this->board->diffPieces($before, $after) as $diffPiece) {
                    if ($diffPiece->color === $piece->color) {
                        $this->result[$piece->color] += self::$value[$piece->id];
                        $this->elaborate($piece);
                    }
                }
                $this->board->attach($piece);
                $this->board->refresh();
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
        $pPhrase = PiecePhrase::create($piece);
        $cPhrase = ColorPhrase::sentence($piece->oppColor());

        $this->elaboration[] = "The $cPhrase king can be put in check as long as $pPhrase moves out of the way.";
    }
}
