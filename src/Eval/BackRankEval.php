<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Back-Rank Evaluation
 *
 * A back-rank checkmate is a checkmate delivered by a rook or queen along the
 * opponent's back rank. The mated king is unable to move up the board because
 * it is blocked by friendly pawns on the second rank.
 */
class BackRankEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface,
    InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'BackRank';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'Black',
            'White',
        ];

        $this->observation = [
            "has a back-rank advantage",
        ];


        // TODO ...

        $this->explain($this->result);
    }

    private function elaborate(AbstractPiece $king): void
    {
        $phrase = PiecePhrase::create($king);
        $this->elaboration[] = ucfirst("$phrase is vulnerable to back-rank checkmate.");
    }
}
