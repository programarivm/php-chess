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
                    foreach ($legalMoveSquares as $square) {
                        $clone = $this->board->clone();
                        $clone->playLan($clone->turn, $piece->sq . $square);
                        $clone->refresh();

                        $legalMovesCount = count($legalMoveSquares);
                        $primaryDeflectionPhrase = $this->primaryPhrase($piece, $attackingPieces, $legalMovesCount);

                        $this->checkExposedPieceAdvantage($clone, $piece, $primaryDeflectionPhrase);

                        if ($this->deflectionExists) {
                            if($legalMovesCount == 1) {
                                $this->elaboration[0] = str_replace("may well", "will", $this->elaboration[0]);
                            }
                            break;
                        }
                    }
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

    private function checkExposedPieceAdvantage(AbstractBoard $clone, AbstractPiece $piece, string $primaryDeflectionPhrase) {
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
            $this->elaborateExposedPieceAdvantage($primaryDeflectionPhrase, $diffPhrases);
            $this->deflectionExists = true;
        }
    }

    private function elaborateExposedPieceAdvantage(String $phrase, array $diffPhrases): void
    {
        $count = count($diffPhrases);
        if ($count === 1) {
            $diffPhrase = mb_strtolower($diffPhrases[0]);
            $rephrase = str_replace('is unprotected', 'may well be exposed to attack', $diffPhrase);
            $phrase .= $rephrase;
        } elseif ($count > 1) {
            $phrase .= 'these pieces may well be exposed to attack: ';
            $rephrase = '';
            foreach ($diffPhrases as $diffPhrase) {
                $rephrase .= str_replace(' is unprotected.', ', ', $diffPhrase);
            }
            $phrase .= $rephrase;
            $phrase = str_replace(', The', ', the', $phrase);
            $phrase = substr_replace(trim($phrase), '.', -1);
        }

        $this->elaboration[] = $phrase;

        // $this->elaboration[] = substr_replace($phrase, ';', -1);
    }
}
