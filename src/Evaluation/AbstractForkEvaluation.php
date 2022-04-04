<?php

namespace Chess\Evaluation;

use Chess\PGN\Symbol;
use Chess\Piece\Piece;

abstract class AbstractForkEvaluation extends AbstractEvaluation
{
    protected function attackedPieces(Piece $piece)
    {
        $attackedPieces = [];
        foreach ($sqs = $piece->getSquares() as $sq) {
            if ($attackedPiece = $this->board->getPieceBySq($sq)) {
                if ($attackedPiece->getId() !== Symbol::PAWN) {
                    $attackedPieces[] = $attackedPiece;
                }
            }
        }

        return $attackedPieces;
    }

    protected function isKingAttacked(array $attackedPieces)
    {
        foreach ($attackedPieces as $attackedPiece) {
            if ($attackedPiece->getId() === Symbol::KING) {
                return true;
            }
        }

        return false;
    }

    protected function sumValues(Piece $piece, array $attackedPieces)
    {
        $values = 0;
        $pieceValue = $this->value[$piece->getId()];
        foreach ($attackedPieces as $attackedPiece) {
            if ($attackedPiece->getId() !== Symbol::KING) {
                $attackedPieceValue = $this->value[$attackedPiece->getId()];
                if ($pieceValue < $attackedPieceValue) {
                    $values += $attackedPieceValue;
                }
            }
        }

        return $values;
    }
}
