<?php

namespace Chess\Eval;

use Chess\Variant\Classical\PGN\AN\Piece;

class AbsolutePinEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'Absolute pin';

    public function eval(): array
    {
        $checkingPieces = $this->board->checkingPieces();
        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Piece::K) {
                $clone = unserialize(serialize($this->board));
                $clone->detach($clone->getPieceBySq($piece->getSq()));
                $clone->refresh();
                $newCheckingPieces = $clone->checkingPieces();
                if (count($newCheckingPieces) > count($checkingPieces)) {
                    $this->result[$piece->getColor()] += $this->value[$piece->getId()];
                    $this->explain($piece);
                }
            }
        }

        return $this->result;
    }

    private function explain($subject, $target = null)
    {
        $this->explanation[] = "{$subject->getId()} on {$subject->getSq()} is pinned.";

        return $this->explanation;
    }
}
