<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;
use Chess\Piece\Pawn;

class IsolatedPawnEvaluation extends AbstractEvaluation implements InverseEvaluationInterface
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
            if ($piece->getId() === Symbol::PAWN) {
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

        $sqs = [];
        foreach ([ $prevFile, $nextFile ] as $file) {
            if ($file < 'a' || $file > 'h') {
                continue;
            }
            $ranks = range(2, 7);
            $sqsFile = array_map(function($rank) use ($file){
                return $file . $rank;
            }, $ranks);
            $sqs = array_merge($sqs, $sqsFile);
        }

        foreach ($sqs as $sq) {
            if ($nextPiece = $this->board->getPieceBySq($sq)) {
                if ($nextPiece->getId() === Symbol::PAWN && $nextPiece->getColor() === $color) {
                    return 0;
                }
            }
        }
        return 1;
    }
}
