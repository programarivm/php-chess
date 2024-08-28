<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

class DeflectionEval extends AbstractEval implements ElaborateEvalInterface
{
    use ElaborateEvalTrait;

    const NAME = 'Deflection';

    protected bool $deflectionExists = false;
    protected bool $checkExists = false;
    protected bool $mateExists = false;

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;
        $checkingPieces = $this->board->piece($this->board->turn, Piece::K)->attacking();

        foreach ($this->board->pieces($this->board->turn) as $piece) {
            $legalMoveSquares = $this->board->legal($piece->sq);
            $attackingPieces = $checkingPieces + $piece->attacking();

            if (!empty($legalMoveSquares)) {
                $legalMovesCount = count($legalMoveSquares);
                $primaryDeflectionPhrase = $this->primaryPhrase($piece, $attackingPieces, $legalMovesCount);

                $exposedPieceList = $this->checkExposedPieceAdvantage($piece, $legalMoveSquares);
                $protectedPawnsList = $this->checkAdvancedPawnAdvantage($piece, $legalMoveSquares);
                $this->elaborateAdvantage($primaryDeflectionPhrase, $exposedPieceList, $protectedPawnsList, $legalMovesCount);
            }
            if ($this->deflectionExists) {
                break;
            }
        }
    }

    private function primaryPhrase(AbstractPiece $piece, array $attackingPieces, int $legalMovesCount): string {
        $piecePhrase = PiecePhrase::create($piece);
        $basePhrase = $piecePhrase . " is deflected";
        $attackedByPhrase = $this->attackedByPhrase($attackingPieces);

        return ($legalMovesCount > 1 ? "If " : "") . $basePhrase . $attackedByPhrase . ", ";
    }

    private function attackedByPhrase(array $attackingPieces): string {
        $basePhrase = " due to ";
        if (count($attackingPieces) === 1) {
            return $basePhrase . PiecePhrase::create($attackingPieces[0]);
        } elseif (count($attackingPieces) > 1) {
            $squares = array_map(fn($attacking) => PiecePhrase::create($attacking), $attackingPieces);
            $lastSquare = array_pop($squares);
            return $basePhrase . implode(', ', $squares) . ' and ' . $lastSquare;
        }
        
        return "";
    }

    private function checkExposedPieceAdvantage(AbstractPiece $piece, array $legalMoveSquares): array {
        $defendedSquares = $this->board->pieceBySq($piece->sq)->defendedSqs();
        $piecePhrase = [];
        
        foreach ($legalMoveSquares as $square) {
            $clone = $this->board->clone();
            $clone->playLan($clone->turn, $piece->sq . $square);

            $updatedDefendedSquares = $clone->pieceBySq($square)->defendedSqs();
            $undefendedSquares = array_diff($defendedSquares, $updatedDefendedSquares);

            if(!empty($undefendedSquares)) {
                $this->deflectionExists = true;

                foreach ($undefendedSquares as $undefendedSquare) {
                    $piecePhrase[] = PiecePhrase::create($clone->pieceBySq($undefendedSquare));
                    foreach ($clone->pieceBySq($undefendedSquare)->attacking() as $attacker) {
                        $clone->playLan($clone->turn, $attacker->sq . $undefendedSquare);
                        if ($clone->isMate()) {
                            $this->mateExists = true;
                        }
                        else if ($clone->isCheck()) {
                            $this->checkExists = true;
                        }
                        $clone->undo();
                    }
                    
                }

                return $piecePhrase;
            }

            return $piecePhrase;
        }
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
                    $attackersDiff = array_udiff_assoc(
                        $this->board->pieceBySq($pawn)->attacking(),
                        $clone->pieceBySq($pawn)->attacking(),
                        function ($obj1, $obj2) {
                            return strcmp($obj1->sq, $obj2->sq);
                        }
                    );
                    
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

        $exposedPiecePhrase = $this->elaborateExposedPieceAdvantage($exposedPieceList);
        $protectedPawnPhrase = $this->elaborateProtectedPawnAdvantage($protectedPawnsList);
        $elaborationPhrase = $primaryDeflectionPhrase . $exposedPiecePhrase . $protectedPawnPhrase;

        if($legalMovesCount == 1) {
            $elaborationPhrase = str_replace("may well", "will", $elaborationPhrase);
        }
        
        if($this->mateExists) {
            $elaborationPhrase .= "; threatning checkmate";
        }
        if($this->checkExists) {
            $elaborationPhrase .= "; threatning a check";
        }
        $elaborationPhrase .= ".";

        $this->elaboration[] = $elaborationPhrase;
    }

    private function elaborateAdvantageText(array $itemList, string $singularMessage, string $pluralPrefix, string $pluralSuffix): string
    {
        $rephrase = "";
        $count = count($itemList);
        if ($count === 1) {
            $item = mb_strtolower($itemList[0]);
            $rephrase = $item . ' ' . $singularMessage;
        } elseif ($count > 1) {
            $rephrase .= $pluralPrefix;
            foreach ($itemList as $item) {
                $rephrase .= $item . ', ';
            }
            $rephrase = substr_replace(trim($rephrase), '', -1) . ' ' . $pluralSuffix;
        }

        return $rephrase;
    }

    private function elaborateExposedPieceAdvantage(array $exposedPieceList): string
    {
        return $this->elaborateAdvantageText(
            $exposedPieceList,
            'may well be exposed to attack',
            'these pieces may well be exposed to attack: ',
            ''
        );
    }

    private function elaborateProtectedPawnAdvantage(array $protectedPawnsList): string
    {
        return $this->elaborateAdvantageText(
            $protectedPawnsList,
            'is not attacked by it and may well be advanced for promotion',
            'these pawns are not attacked by it and may well be advanced for promotion: ',
            ''
        );
    }
}
