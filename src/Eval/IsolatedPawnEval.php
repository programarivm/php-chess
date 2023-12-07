<?php

namespace Chess\Eval;

use Chess\Piece\P;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class IsolatedPawnEval extends AbstractEval implements InverseEvalInterface
{
    const NAME = 'Isolated pawn';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $sqs = [];
        foreach ($this->board->getPieces() as $piece) {
            $color = $piece->getColor();
            if ($piece->getId() === Piece::P) {
                if ($this->checkIsolatedPawn($piece)) {
                    $this->result[$color] += 1;
                    $sqs[] = $piece->getSq();
                }
            }
        }

        $this->explain($sqs);
    }

    private function checkIsolatedPawn(P $pawn): int
    {
        $color = $pawn->getColor();
        $pawnFile = $pawn->getSqFile();

        $prevFile = chr(ord($pawnFile) - 1);
        $nextFile = chr(ord($pawnFile) + 1);

        $sqs = [];
        foreach ([ $prevFile, $nextFile ] as $file) {
            if ($file < 'a' || $file > 'h') {
                continue;
            }
            $ranks = range(2, 7);
            $sqsFile = array_map(function($rank) use ($file){
                return $file . $rank;
            }, $ranks);
            $sqs = [...$sqs, ...$sqsFile];
        }

        foreach ($sqs as $sq) {
            if ($nextPiece = $this->board->getPieceBySq($sq)) {
                if ($nextPiece->getId() === Piece::P && $nextPiece->getColor() === $color) {
                    return 0;
                }
            }
        }

        return 1;
    }

    private function explain(array $sqs): void
    {
        if (count($sqs) > 1) {
            $str = 'The pawns on ';
            $keys = array_keys($sqs);
            $lastKey = end($keys);
            foreach ($sqs as $key => $val) {
                if ($key === $lastKey) {
                    $str = substr($str, 0, -2);
                    $str .= " and $val are isolated.";
                } else {
                    $str .= "$val, ";
                }
            }
            $this->phrases = [
                $str,
            ];
        } elseif (count($sqs) === 1) {
            $this->phrases = [
                "The pawn on $sqs[0] is isolated.",
            ];
        }
    }
}
