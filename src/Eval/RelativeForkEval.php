<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class RelativeForkEval extends AbstractEval
{
    const NAME = 'Relative fork';

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            if (!$piece->isAttackingKing()) {
                $attackedPieces = $piece->attackedPieces();
                if (count($attackedPieces) >= 2) {
                    foreach ($attackedPieces as $attackedPiece) {
                        if ($attackedPiece->getId() !== Piece::K) {
                            $this->result[$piece->getColor()] += self::$value[$attackedPiece->getId()];
                            $this->explain($attackedPiece);
                        }
                    }

                }
            }
        }
    }

    private function explain($subject, $target = null)
    {
        $phrase = PiecePhrase::deterministic($subject);
        $this->explanation[] = "Relative fork attack on {$phrase}.";

        return $this->explanation;
    }
}
