<?php

namespace Chess\Eval;

use Chess\Eval\SqOutpostEval;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/*
 * Knight Outpost Evaluation
 *
 * A knight on an outpost square is considered a positional advantage because
 * it cannot be attacked by enemy pawns, and as a result, it is often exchanged
 * for a bishop.
 */
class KnightOutpostEval extends AbstractEval
{
    use ElaborateEvalTrait;

    /*
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Knight outpost';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     * @param \Chess\Eval\SqOutpostEval $dependsOn
     */
    public function __construct(AbstractBoard $board, SqOutpostEval $dependsOn)
    {
        $this->board = $board;

        $sqOutpostEval = $dependsOn->getResult();

        foreach ($sqOutpostEval as $key => $val) {
            foreach ($val as $sq) {
                if ($piece = $this->board->pieceBySq($sq)) {
                    if ($piece->color === $key && $piece->id === Piece::N) {
                        $this->result[$key] += 1;
                        $this->elaborate($piece);
                    }
                }
            }
        }
    }

    /**
     * Elaborate on the evaluation.
     *
     * @param \Chess\Variant\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->elaboration[] = ucfirst("$phrase is nicely placed on an outpost.");
    }
}
