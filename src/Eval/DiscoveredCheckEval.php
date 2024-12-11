<?php

namespace Chess\Eval;

use Chess\Tutor\ColorPhrase;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Discovered Check Evaluation
 *
 * Evaluates the advantage gained as a result of the existence of discovered
 * checks. A discovered check occurs when the opponent's king can be checked by
 * moving a piece out of the way of another.
 */
class DiscoveredCheckEval extends AbstractEval
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait {
        explain as private doExplain;
    }

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
                        $this->toElaborate[] = $piece;
                    }
                }
                $this->board->attach($piece);
                $this->board->refresh();
            }
        }
    }

    /**
     * Explain the evaluation.
     *
     * @return array
     */
    public function explain(): array
    {
        $this->doExplain($this->result);

        return $this->explanation;
    }

    /**
     * Elaborate on the evaluation.
     *
     * @return array
     */
    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $pPhrase = PiecePhrase::create($val);
            $cPhrase = ColorPhrase::sentence($val->oppColor());
            $this->elaboration[] = "The $cPhrase king can be put in check as long as $pPhrase moves out of the way.";
        }

        return $this->elaboration;
    }
}
