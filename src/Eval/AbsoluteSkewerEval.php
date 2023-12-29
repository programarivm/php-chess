<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class AbsoluteSkewerEval extends AbstractEval
{
    const NAME = 'Absolute skewer';

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->isAttackingKing()) {
                $king = $this->board->getPiece($this->board->getTurn(), Piece::K);
                $clone = unserialize(serialize($this->board));
                $clone->playLan($clone->getTurn(), $king->getSq().current($king->sqs()));
                $attackedPieces = $piece->attackedPieces();
                $newAttackedPieces = $clone->getPieceBySq($piece->getSq())->attackedPieces();
                if ($attackedPiece = $this->attackedPiece($attackedPieces, $newAttackedPieces)) {
                    if (self::$value[$piece->getId()] < self::$value[$attackedPiece->getId()]) {
                        $this->result[$piece->getColor()] = 1;
                        $this->explain($piece, $king);
                    }
                }
            }
        }
    }

    private function attackedPiece(array $attackedPieces, array $newAttackedPieces)
    {
        $a = array_map(function($attackedPiece) {
            return $attackedPiece->getSq();
        }, $attackedPieces);

        $b = array_map(function($newAttackedPiece) {
            return $newAttackedPiece->getSq();
        }, $newAttackedPieces);

        foreach ($b as $sq) {
            if (!in_array($sq, $a)) {
                return $this->board->getPieceBySq($sq);
            }
        }

        return null;
    }

    private function explain(AbstractPiece $attackingPiece, AbstractPiece $attackedPiece): void
    {
        $attacking = PiecePhrase::create($attackingPiece);
        $attacked = PiecePhrase::create($attackedPiece);

        $this->phrases[] = ucfirst("when $attacked will be moved, a piece that is more valuable than $attacking will be exposed to attack.");
    }
}
