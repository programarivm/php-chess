<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;
use Chess\Piece\Pawn;

class IsolatedPawnEvaluation extends AbstractEvaluation
{
    const NAME = 'isolated_pawn';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function evaluate(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            /** @var Pawn $piece */
            if ($piece->getIdentity() === Symbol::PAWN) {
                $this->result[ $color ] += $this->checkIsolatedPawn($piece);
            }
        }
        return $this->result;
    }

    private function checkIsolatedPawn(Pawn $pawn): int
    {
        $color = $pawn->getColor();
        $pawnFile = $pawn->getFile();

        $prevFile = chr(ord($pawnFile) - 1);
        $nextFile = chr(ord($pawnFile) + 1);

        $squares = [];
        foreach ([ $prevFile, $nextFile ] as $file) {
            if ($file < 'a' || $file > 'h') {
                continue;
            }
            $ranks = range(2, 7);
            $squaresFile = array_map(function($rank) use ($file){
                return $file . $rank;
            }, $ranks);
            $squares = array_merge($squares, $squaresFile);
        }

        foreach ($squares as $square) {
            if ($nextPiece = $this->board->getPieceByPosition($square)) {
                if ($nextPiece->getIdentity() === Symbol::PAWN && $nextPiece->getColor() === $color) {
                    return 0;
                }
            }
        }
        return 1;
    }
}
