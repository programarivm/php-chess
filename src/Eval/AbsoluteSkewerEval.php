<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

class AbsoluteSkewerEval extends AbstractEval implements ElaborateEvalInterface
{
    use ElaborateEvalTrait;

    const NAME = 'Absolute skewer';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        foreach ($this->board->pieces() as $piece) {
            if ($piece->isAttackingKing()) {
                $king = $this->board->piece($this->board->turn, Piece::K);
                $clone = $this->board->clone();
                $clone->playLan($clone->turn, $king->sq . current($king->moveSqs()));
                $attacked = $piece->attacked();
                $newAttacked = $clone->pieceBySq($piece->sq)->attacked();
                if ($diffPieces = $this->board->diffPieces($attacked, $newAttacked)) {
                    if (self::$value[$piece->id] < self::$value[current($diffPieces)->id]) {
                        $this->result[$piece->color] = 1;
                        $this->elaborate($piece, $king);
                    }
                }
            }
        }
    }

    private function elaborate(AbstractPiece $attacking, AbstractPiece $attacked): void
    {
        $attacking = PiecePhrase::create($attacking);
        $attacked = PiecePhrase::create($attacked);

        $this->elaboration[] = ucfirst("when $attacked will be moved, a piece that is more valuable than $attacking may well be exposed to attack.");
    }
}
