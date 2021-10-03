<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;
use Chess\Piece\Pawn;

class PassedPawnEvaluation extends AbstractEvaluation
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
            /** @var Pawn $piece */
            if ($piece->getIdentity() === Symbol::PAWN) {
                $this->result[ $color ] += $this->getThreatPassedPawn($piece);
            }
        }
        return $this->result;
    }

    private function getThreatPassedPawn(Pawn $pawn): int
    {
        $color = $pawn->getColor();
        $pawnFile = $pawn->getFile();
        $ranks = $pawn->getRanks();

        $prevFile = chr(ord($pawnFile) - 1);
        $nextFile = chr(ord($pawnFile) + 1);

        $squares = [];
        foreach ([ $prevFile, $pawnFile, $nextFile ] as $file) {
            if ($file < 'a' || $file > 'h') {
                continue;
            }
            if ($color === Symbol::WHITE) {
                $listRanks = range($ranks->next, $ranks->promotion - 1);
            } else {
                $listRanks = range($ranks->next, $ranks->promotion + 1);
            }
            $squaresFile = array_map(function($rank) use ($file){
                return $file . $rank;
            }, $listRanks);
            $squares = array_merge($squares, $squaresFile);
        }
        $passedPawn = true;
        foreach ($squares as $square) {
            if ($nextPiece = $this->board->getPieceByPosition($square)) {
                if ($nextPiece->getIdentity() === Symbol::PAWN && $nextPiece->getColor() !== $color) {
                    $passedPawn = false;
                    break;
                }
            }
        }
        if ($passedPawn) {
            $position = $pawn->getPosition();
            if ($color === Symbol::WHITE) {
                return $position[ 1 ];
            } else {
                return 9 - $position[ 1 ];
            }
        }

        return 0;
    }
}
