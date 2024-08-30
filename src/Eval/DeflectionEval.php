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
            $defendedSquares = $this->board->pieceBySq($piece->sq)->defendedSqs();
            // considering deflection due to pieces checking the king or attacking the piece
            $attackingPieces = $checkingPieces + $piece->attacking();

            // for optimisation, sorting legal moves to check cases of capturing attacking pieces first, as they would majorly lead to deflection cases
            $this->sortLegalMoves($legalMoveSquares, $attackingPieces);

            if (!empty($legalMoveSquares) && !empty($attackingPieces)) {
                $legalMovesCount = count($legalMoveSquares);
                $primaryDeflectionPhrase = $this->primaryPhrase($piece, $attackingPieces, $legalMovesCount);

                foreach ($legalMoveSquares as $square) {
                    $clone = $this->board->clone();
                    $clone->playLan($clone->turn, $piece->sq . $square);

                    // checks for exposed pieces and protected pawns to ensure deflection
                    $exposedPieceList = $this->checkExposedPieceAdvantage($clone, $defendedSquares, $square);
                    $protectedPawnsList = $this->checkAdvancedPawnAdvantage($clone, $piece);

                    if (!empty($exposedPieceList) || !empty($protectedPawnsList)) {
                        $this->deflectionExists = true;
                        $this->elaborateAdvantage($primaryDeflectionPhrase, $exposedPieceList, $protectedPawnsList, $legalMovesCount);
                        break;
                    }
                }
            }
            if ($this->deflectionExists) {
                break;
            }
        }
    }

    /**
     * sorts the legal moves array, so that it arranges the moves for capturing the attacking pieces on top
     */
    private function sortLegalMoves(&$legalMoveSquares, $attackingPieces)
    {
        usort($legalMoveSquares, function ($a, $b) use ($attackingPieces) {
            $aInAttacking = array_key_exists($a, $attackingPieces);
            $bInAttacking = array_key_exists($b, $attackingPieces);

            if ($aInAttacking && !$bInAttacking) {
                return -1;
            } elseif (!$aInAttacking && $bInAttacking) {
                return 1;
            } else {
                return 0;
            }
        });
    }

    private function primaryPhrase(AbstractPiece $piece, array $attackingPieces, int $legalMovesCount): string
    {
        $piecePhrase = PiecePhrase::create($piece);
        $basePhrase = $piecePhrase . " is deflected";
        $attackedByPhrase = $this->attackedByPhrase($attackingPieces);

        return ($legalMovesCount > 1 ? "If " : "") . $basePhrase . $attackedByPhrase . ", ";
    }

    private function attackedByPhrase(array $attackingPieces): string
    {
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

    /**
     * checks for exposed pieces due to deflection, also checks for possibility of check or mate to the king
     */
    private function checkExposedPieceAdvantage(AbstractBoard $clone, array $defendedSquares, string $square): array
    {
        $piecePhrase = [];

        $updatedDefendedSquares = $clone->pieceBySq($square)->defendedSqs();
        $undefendedSquares = array_diff($defendedSquares, $updatedDefendedSquares);

        if (!empty($undefendedSquares)) {
            foreach ($undefendedSquares as $undefendedSquare) {
                $attackers = $clone->pieceBySq($undefendedSquare)->attacking();
                if (!empty($attackers)) {
                    $piecePhrase[] = PiecePhrase::create($clone->pieceBySq($undefendedSquare));

                    foreach ($attackers as $attacker) {
                        $clone->playLan($clone->turn, $attacker->sq . $undefendedSquare);
                        if ($clone->isMate()) {
                            $this->mateExists = true;
                        } else if ($clone->isCheck()) {
                            $this->checkExists = true;
                        }
                        $clone->undo();
                    }
                }
            }
        }

        return $piecePhrase;
    }

    /**
     * checks for the advanced pawns that become protected due to deflection
     */
    private function checkAdvancedPawnAdvantage(AbstractBoard $clone, AbstractPiece $piece): array
    {
        $pawnsList = [];

        $advancedPawnEval = new FarAdvancedPawnEval($clone);
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

        return $pawnsList;
    }

    private function elaborateAdvantage(String $primaryDeflectionPhrase, array $exposedPieceList, array $protectedPawnsList, int $legalMovesCount)
    {
        if (!$this->deflectionExists) {
            return;
        }

        $exposedPiecePhrase = $this->elaborateExposedPieceAdvantage($exposedPieceList);
        $protectedPawnPhrase = $this->elaborateProtectedPawnAdvantage($protectedPawnsList);
        $elaborationPhrase = $primaryDeflectionPhrase . $exposedPiecePhrase . $protectedPawnPhrase;

        // in case of only moves, also presenting more details
        if ($legalMovesCount == 1) {
            $elaborationPhrase = str_replace("may well", "will", $elaborationPhrase);
            if ($this->mateExists) {
                $elaborationPhrase .= "; threatning checkmate";
            }
            if ($this->checkExists) {
                $elaborationPhrase .= "; threatning a check";
            }
        }

        $elaborationPhrase .= ".";

        $this->elaboration[] = $elaborationPhrase;
    }

    private function elaborateAdvantageText(array $itemList, string $singularMessage, string $pluralPrefix): string
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
            $rephrase = substr_replace(trim($rephrase), '', -1);
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
