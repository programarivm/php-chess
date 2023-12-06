<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Piece\P;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

class PassedPawnEval extends AbstractEval
{
    const NAME = 'Passed pawn';

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            if ($piece->getId() === Piece::P) {
                $this->result[ $color ] += $this->getThreatPassedPawn($piece);
            }
        }
    }

    private function getThreatPassedPawn(P $pawn): int
    {
        $pawnFile = $pawn->getSqFile();
        $prevFile = chr(ord($pawnFile) - 1);
        $nextFile = chr(ord($pawnFile) + 1);

        $sqs = [];
        foreach ([ $prevFile, $pawnFile, $nextFile ] as $file) {
            if ($file < 'a' || $file > 'h') {
                continue;
            }
            if ($pawn->getColor() === Color::W) {
                $listRanks = range($pawn->getRanks()->next, $pawn->getRanks()->end - 1);
            } else {
                $listRanks = range($pawn->getRanks()->next, $pawn->getRanks()->end + 1);
            }
            $sqsFile = array_map(function($rank) use ($file) {
                return $file . $rank;
            }, $listRanks);
            $sqs = [...$sqs, ...$sqsFile];
        }

        $passedPawn = true;
        foreach ($sqs as $sq) {
            if ($nextPiece = $this->board->getPieceBySq($sq)) {
                if (
                    $nextPiece->getId() === Piece::P &&
                    $nextPiece->getColor() !== $pawn->getColor()
                ) {
                    $passedPawn = false;
                    break;
                }
            }
        }

        if ($passedPawn) {
            $this->explain($pawn);
            if ($pawn->getColor() === Color::W) {
                return $pawn->getSq()[1];
            } else {
                return 9 - $pawn->getSq()[1];
            }
        }

        return 0;
    }

    private function explain(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->phrases[] = ucfirst("{$phrase} is passed.");
    }
}
