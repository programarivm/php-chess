<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;

class DeflectionEval extends AbstractEval implements ElaborateEvalInterface
{
    use ElaborateEvalTrait;

    const NAME = 'Deflection';

    protected bool $deflectionExists = false;

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        foreach ($this->board->pieces() as $piece) {
            if ($piece->color === $this->board->turn) {
                $legalMoveSquares = $board->legal($piece->sq);
                $attackingPieces = $piece->attacking();

                if (!empty($attackingPieces) && !empty($legalMoveSquares)) {
                    $legalMovesCount = count($legalMoveSquares);
                    $primaryDeflectionPhrase = $this->primaryPhrase($piece, $attackingPieces, $legalMovesCount);

                    $exposedPieceList = $this->checkExposedPieceAdvantage($piece);
                    $protectedPawnsList = $this->checkAdvancedPawnAdvantage($piece, $legalMoveSquares);
                    $this->elaborateAdvantage($primaryDeflectionPhrase, $exposedPieceList, $protectedPawnsList, $legalMovesCount);
                }
            }
            if ($this->deflectionExists) {
                break;
            }
        }
    }

    private function primaryPhrase(AbstractPiece $piece, array $attackingPieces, int $legalMovesCount): string {
        $piecePhrase = PiecePhrase::create($piece);
        $attackedByPhrase = $this->attackedByPhrase($attackingPieces);

        return ($legalMovesCount > 1 ? "If " : "") . "$piecePhrase is deflected due to $attackedByPhrase, ";
    }

    private function attackedByPhrase(array $attackingPieces): string {
        if (count($attackingPieces) === 1) {
            return PiecePhrase::create($attackingPieces[0]);
        } elseif (count($attackingPieces) > 1) {
            $squares = array_map(fn($attacking) => PiecePhrase::create($attacking), $attackingPieces);
            $lastSquare = array_pop($squares);
            return implode(', ', $squares) . ' and ' . $lastSquare;
        }
    }

    private function checkExposedPieceAdvantage(AbstractPiece $piece): array {
        $clone = $this->board->clone();
        $clone->detach($clone->pieceBySq($piece->sq));
        $clone->refresh();
        
        $diffPhrases = [];
        $protectionEval = new ProtectionEval($this->board);
        $newProtectionEval = new ProtectionEval($clone);
        $diffResult = $newProtectionEval->getResult()[$piece->oppColor()]
            - $protectionEval->getResult()[$piece->oppColor()];
        
        if ($diffResult > 0) {
            foreach ($newProtectionEval->getElaboration() as $key => $val) {
                if (!in_array($val, $protectionEval->getElaboration())) {
                    $diffPhrases[] = $val;
                }
            }
            $this->deflectionExists = true;
        }

        return $diffPhrases;
    }

    private function checkAdvancedPawnAdvantage(AbstractPiece $piece, array $legalMoveSquares): array {
        $pawnsList = [];

        foreach ($legalMoveSquares as $square) {
            $clone = $this->board->clone();
            $clone->playLan($clone->turn, $piece->sq . $square);
            
            $advancedPawnEval = new AdvancedPawnEval($this->board);
            $advancedPawns = $advancedPawnEval->getResult()[$clone->turn];

            if (!empty($advancedPawns)) {
                foreach ($advancedPawns as $pawn) {
                    $attackersDiff = array_diff_assoc($this->board->pieceBySq($pawn)->attacking(), $clone->pieceBySq($pawn)->attacking());
                    foreach ($attackersDiff as $attacker) {
                        if ($attacker->sq === $piece->sq) {
                            $pawnsList[] = PiecePhrase::create($this->board->pieceBySq($pawn));
                        }
                    }
                }
            }
            if (!empty($pawnsList)) {
                $this->deflectionExists = true;
                break;
            }
        }

        return $pawnsList;
    }

    private function elaborateAdvantage(String $primaryDeflectionPhrase, array $exposedPieceList, array $protectedPawnsList, int $legalMovesCount) {
        if (!$this->deflectionExists) {
            return;
        }

        $elaborationPhase = $this->elaborateExposedPieceAdvantage($primaryDeflectionPhrase, $exposedPieceList);
        $protectedPawnPhrase = $this->elaborateProtectedPawnAdvantage($primaryDeflectionPhrase, $protectedPawnsList);
        $this->elaboration[] = $primaryDeflectionPhrase . $elaborationPhase . $protectedPawnPhrase;
        if($legalMovesCount == 1) {
            $this->elaboration[0] = str_replace("may well", "will", $this->elaboration[0]);
        }
    }

    private function elaborateExposedPieceAdvantage(String $phrase, array $exposedPieceList): string
    {
        $rephrase = "";
        $count = count($exposedPieceList);
        if ($count === 1) {
            $exposedPiece = mb_strtolower($exposedPieceList[0]);
            $rephrase = str_replace('is unprotected', 'may well be exposed to attack', $exposedPiece);
        } elseif ($count > 1) {
            $phrase .= 'these pieces may well be exposed to attack: ';
            $rephrase = '';
            foreach ($exposedPieceList as $exposedPiece) {
                $rephrase .= str_replace(' is unprotected.', ', ', $exposedPiece);
            }
            $rephrase = str_replace(', The', ', the', $rephrase);
            $rephrase = substr_replace(trim($rephrase), '.', -1);
        }

        return $rephrase;
    }

    private function elaborateProtectedPawnAdvantage(String $phrase, array $protectedPawnsList): string
    {
        $rephrase = "";
        $count = count($protectedPawnsList);
        if ($count === 1) {
            $protectedPawn = mb_strtolower($protectedPawnsList[0]);
            $rephrase = $protectedPawn . " is not threatened and may well be advanced for promotion.";
        } elseif ($count > 1) {
            $phrase .= 'these pawns are not attacked and may well be advanced for promotion: ';
            $rephrase = '';
            foreach ($protectedPawnsList as $protectedPawn) {
                $rephrase .= str_replace(' is unprotected.', ', ', $protectedPawn);
            }
            $rephrase = str_replace(', The', ', the', $rephrase);
            $rephrase = substr_replace(trim($rephrase), '.', -1);
        }

        return $rephrase;
    }

}
