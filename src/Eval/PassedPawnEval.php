<?php

namespace Chess\Eval;

use Chess\Board;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Piece\P;

class PassedPawnEval extends AbstractEval
{
    const NAME = 'Passed pawn';

    public function __construct(Board $board)
    {
        parent::__construct($board);

        $this->result = [
            Color::W => 0,
            Color::B => 0,
        ];
    }

    public function eval(): array
    {
        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            if ($piece->getId() === Piece::P) {
                $this->result[ $color ] += $this->getThreatPassedPawn($piece);
            }
        }
        return $this->result;
    }

    private function getThreatPassedPawn(P $pawn): int
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
            if ($color === Color::W) {
                $listRanks = range($ranks->next, $ranks->end - 1);
            } else {
                $listRanks = range($ranks->next, $ranks->end + 1);
            }
            $sqsFile = array_map(function($rank) use ($file){
                return $file . $rank;
            }, $listRanks);
            $sqs = [...$sqs, ...$sqsFile];
        }
        $passedPawn = true;
        foreach ($sqs as $sq) {
            if ($nextPiece = $this->board->getPieceBySq($sq)) {
                if ($nextPiece->getId() === Piece::P && $nextPiece->getColor() !== $color) {
                    $passedPawn = false;
                    break;
                }
            }
        }
        if ($passedPawn) {
            $position = $pawn->getSq();
            if ($color === Color::W) {
                return $position[ 1 ];
            } else {
                return 9 - $position[ 1 ];
            }
        }

        return 0;
    }
}
