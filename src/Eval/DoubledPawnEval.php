<?php

namespace Chess\Eval;

use Chess\Tutor\DoubledPawnEvalSentence;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class DoubledPawnEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'Doubled pawn';

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            if ($piece->getId() === Piece::P) {
                $file = $piece->getSqFile();
                $ranks = $piece->getRanks();
                if ($nextPiece = $this->board->getPieceBySq($file.$ranks->next)) {
                    if ($nextPiece->getId() === Piece::P && $nextPiece->getColor() === $color) {
                        $this->result[$color] += 1;
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    private function explain($subject, $target = null)
    {
        if ($sentence = DoubledPawnEvalSentence::predictable($subject)) {
            $this->phrases[] = $sentence;
        }

        return $this->phrases;
    }
}
