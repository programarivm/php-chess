<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;

class ThreatEval extends AbstractEval
{
    const NAME = 'Threat';

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            $countAttacking = count($piece->attackingPieces());
            $countDefending = count($piece->defendingPieces());
            $diff = $countAttacking - $countDefending;
            if ($diff > 0) {
                $this->result[$piece->getColor()] += $diff;
                $this->explain($piece);
            }
        }
    }

    private function explain(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->phrases[] = ucfirst("$phrase is being threatened and may be lost if not defended properly.");
    }
}
