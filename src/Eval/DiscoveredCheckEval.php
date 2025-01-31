<?php

namespace Chess\Eval;

use Chess\Phrase\ColorPhrase;
use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractLinePiece;
use Chess\Variant\Classical\PGN\Piece;

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
                foreach ($piece->defending() as $defending) {
                    if (is_a($defending, AbstractLinePiece::class)) {
                        if ($this->board->square->isBetweenSqs($king->sq, $piece->sq, $defending->sq) &&
                            $this->board->isEmptyLine($this->board->square->line($piece->sq, $king->sq))
                        ) {
                            $this->result[$piece->color] += self::$value[$piece->id];
                            $this->toElaborate[] = $piece;
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
            $this->elaboration[] = "The " . 
                ColorPhrase::sentence($val->oppColor()) .
                " king can be put in check as long as " .
                PiecePhrase::create($val) .
                " moves out of the way.";
        }

        return $this->elaboration;
    }
}
