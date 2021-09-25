<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;

class DoubledPawnEvaluation extends AbstractEvaluation
{
    const NAME = 'doubled_pawn';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate($feature = null): array
    {
        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            if ($piece->getIdentity() === Symbol::PAWN) {
                $file = $piece->getFile();
                $ranks = $piece->getRanks();
                if ($nextPiece = $this->board->getPieceByPosition($file.$ranks->next)) {
                    if ($nextPiece->getIdentity() === Symbol::PAWN && $nextPiece->getColor() === $color) {
                        $this->result[$color] += 1;
                    }
                }
            }
        }

        return $this->result;
    }
}
