<?php

namespace Chess\Eval;

use Chess\Eval\SqOutpostEval;
use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\Board;

class KnightOutpostEval extends AbstractEval implements ElaborateEvalInterface
{
    use ElaborateEvalTrait;

    const NAME = 'Knight outpost';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $sqOutpostEval = (new SqOutpostEval($board))->getResult();

        foreach ($sqOutpostEval as $key => $val) {
            foreach ($val as $sq) {
                if ($piece = $this->board->getPieceBySq($sq)) {
                    if ($piece->color === $key && $piece->id === Piece::N) {
                        $this->result[$key] += 1;
                        $this->elaborate($piece);
                    }
                }
            }
        }
    }

    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->elaboration[] = ucfirst("$phrase is nicely placed on an outpost.");
    }
}
