<?php

namespace Chess\Eval;

use Chess\Eval\SqOutpostEval;
use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

/*
 * Bishop Outpost Evaluation
 *
 * A bishop on an outpost square is considered a positional advantage because
 * it cannot be attacked by enemy pawns, and as a result, it is often exchanged
 * for another piece.
 */
class BishopOutpostEval extends AbstractEval
{
    use ElaborateEvalTrait;

    /*
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Bishop outpost';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $sqOutpostEval = (new SqOutpostEval($this->board))->result;

        foreach ($sqOutpostEval as $key => $val) {
            foreach ($val as $sq) {
                if ($piece = $this->board->pieceBySq($sq)) {
                    if ($piece->color === $key && $piece->id === Piece::B) {
                        $this->result[$key] += 1;
                        $this->toElaborate[] = $piece;
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
            $this->elaboration[] = ucfirst(PiecePhrase::create($val)) .
                " is nicely placed on an outpost.";
        }

        return $this->elaboration;
    }
}
