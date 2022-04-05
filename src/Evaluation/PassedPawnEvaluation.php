<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;
use Chess\Piece\Pawn;

class PassedPawnEvaluation extends AbstractEvaluation
{
    const NAME = 'passed_pawn';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];
    }

    public function eval(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            /** @var Pawn $piece */
            if ($piece->getId() === Symbol::P) {
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

        $sqs = [];
        foreach ([ $prevFile, $pawnFile, $nextFile ] as $file) {
            if ($file < 'a' || $file > 'h') {
                continue;
            }
            if ($color === Symbol::WHITE) {
                $listRanks = range($ranks->next, $ranks->promotion - 1);
            } else {
                $listRanks = range($ranks->next, $ranks->promotion + 1);
            }
            $sqsFile = array_map(function($rank) use ($file){
                return $file . $rank;
            }, $listRanks);
            $sqs = array_merge($sqs, $sqsFile);
        }
        $passedPawn = true;
        foreach ($sqs as $sq) {
            if ($nextPiece = $this->board->getPieceBySq($sq)) {
                if ($nextPiece->getId() === Symbol::P && $nextPiece->getColor() !== $color) {
                    $passedPawn = false;
                    break;
                }
            }
        }
        if ($passedPawn) {
            $position = $pawn->getSquare();
            if ($color === Symbol::WHITE) {
                return $position[ 1 ];
            } else {
                return 9 - $position[ 1 ];
            }
        }

        return 0;
    }
}
