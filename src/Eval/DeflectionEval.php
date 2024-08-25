<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;

class DeflectionEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Deflection';

    protected bool $deflectionExists = false;

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;
        
        $this->range = [1, 6];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight deflection advantage",
            "has a moderate deflection advantage",
            "has a total deflection advantage",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->color === $this->board->turn) {
                $legalMoveSquares = $board->legal($piece->sq);
                $attackingPieces = $piece->attacking();

                if (!empty($attackingPieces) && !empty($legalMoveSquares)) {
                    foreach ($legalMoveSquares as $square) {
                        $clone = $this->board->clone();
                        $clone->playLan($clone->turn, $piece->sq . $square);
                        $clone->refresh();

                        $primaryDeflectionPhrase = $this->primaryPhrase($piece, $attackingPieces);

                        $this->checkExposedPieceAdvantage($clone, $piece, $primaryDeflectionPhrase);

                        if ($this->deflectionExists) {
                            break;
                        }
                    }
                }
            }
            if ($this->deflectionExists) {
                break;
            }
        }

        $this->explain($this->result);
    }

    private function primaryPhrase(AbstractPiece $piece, array $attackingPieces): string {
        $piecePhrase = PiecePhrase::create($piece);

        $attackedByPhrase = $this->attackedByPhrase($attackingPieces);

        return "$piecePhrase is deflected due to $attackedByPhrase, ";
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
            $this->result[$piece->oppColor()] += round($diffResult, 2);
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
