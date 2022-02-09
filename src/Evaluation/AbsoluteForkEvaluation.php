<?php

namespace Chess\Evaluation;

use Chess\Board;
use Chess\PGN\Symbol;
use Chess\Piece\Piece;

class AbsoluteForkEvaluation extends AbstractEvaluation
{
    const NAME = 'absolute_fork';

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
            if ($piece->getIdentity() !== Symbol::KING) {
                $attackedPieces = $this->attackedPieces($piece);
                if ($this->isKingAttacked($attackedPieces)) {
                    $this->result[$piece->getColor()] = $this->sumValues($piece, $attackedPieces);
                }
            }
        }

        return $this->result;
    }

    private function attackedPieces(Piece $piece)
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

    private function isKingAttacked(array $attackedPieces)
    {
        foreach ($attackedPieces as $attackedPiece) {
            if ($attackedPiece->getIdentity() === Symbol::KING) {
                return true;
            }
        }

        return false;
    }

    private function sumValues(Piece $piece, array $attackedPieces)
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
