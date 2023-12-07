<?php

namespace Chess\Eval;

use Chess\Eval\AttackEval;
use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class RelativePinEval extends AbstractEval
{
    const NAME = 'Relative pin';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $attackEval = (new AttackEval($this->board))->getResult();

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Piece::K && $piece->getId() !== Piece::Q) {
                $clone = unserialize(serialize($this->board));
                $clone->detach($clone->getPieceBySq($piece->getSq()));
                $clone->refresh();
                $newAttackEval = (new AttackEval($clone))->getResult();
                $attackEvalDiff = $newAttackEval[$piece->oppColor()] - $attackEval[$piece->oppColor()];
                if ($attackEvalDiff > 0) {
                    $this->result[$piece->oppColor()] += round($attackEvalDiff, 2);
                    $this->explain($piece);
                }
            }
        }
    }

    private function explain(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->phrases[] = "{$phrase} is pinned shielding a piece that is more valuable than the attacking piece.";
    }
}
