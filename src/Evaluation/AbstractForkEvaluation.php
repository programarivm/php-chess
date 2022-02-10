<?php

namespace Chess\Evaluation;

use Chess\PGN\Symbol;
use Chess\Piece\Piece;

abstract class AbstractForkEvaluation extends AbstractEvaluation
{
    protected function attackedPieces(Piece $piece)
    {
        $attackedPieces = [];
        foreach ($legalMoves = $piece->getLegalMoves() as $legalMove) {
            if ($attackedPiece = $this->board->getPieceByPosition($legalMove)) {
                if ($attackedPiece->getIdentity() !== Symbol::PAWN) {
                    $attackedPieces[] = $attackedPiece;
                }
            }
        }

        return $attackedPieces;
    }

    protected function isKingAttacked(array $attackedPieces)
    {
        foreach ($attackedPieces as $attackedPiece) {
            if ($attackedPiece->getIdentity() === Symbol::KING) {
                return true;
            }
        }

        return false;
    }

    protected function sumValues(Piece $piece, array $attackedPieces)
    {
        $values = 0;
        $pieceValue = $this->value[$piece->getIdentity()];
        foreach ($attackedPieces as $attackedPiece) {
            if ($attackedPiece->getIdentity() !== Symbol::KING) {
                $attackedPieceValue = $this->value[$attackedPiece->getIdentity()];
                if ($pieceValue < $attackedPieceValue) {
                    $values += $attackedPieceValue;
                }
            }
        }

        return $values;
    }
}
