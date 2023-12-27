<?php

namespace Chess\Eval;

use Chess\Eval\ProtectionEval;
use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class RelativeSkewerEval extends AbstractEval
{
    const NAME = 'Relative skewer';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $protectionEval = (new ProtectionEval($this->board))->getResult();

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Piece::K) {
                if (!empty($piece->attackingPieces())) {
                    $clone = unserialize(serialize($this->board));
                    $clone->detach($clone->getPieceBySq($piece->getSq()));
                    $clone->refresh();
                    $newProtectionEval = (new ProtectionEval($clone))->getResult();
                    $protectionEvalDiff = $newProtectionEval[$piece->oppColor()] - $protectionEval[$piece->oppColor()];
                    if ($protectionEvalDiff > 0) {
                        $this->result[$piece->oppColor()] += round($protectionEvalDiff, 2);
                        $this->explain($piece);
                    }
                }
            }
        }
    }

    private function explain(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->phrases[] = ucfirst("$phrase is under attack because it is shielding a piece that is unprotected.");
    }
}
