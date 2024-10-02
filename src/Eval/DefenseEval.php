<?php

namespace Chess\Eval;

use Chess\Eval\ProtectionEval;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/*
 * Defense Evaluation
 *
 * This heuristic evaluates the defensive strength of each side by analyzing
 * how the removal of attacking pieces would affect the opponent's protection.
 * A higher score indicates a stronger defensive position.
 */
class DefenseEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    /*
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Defense';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 9];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight defense advantage",
            "has a moderate defense advantage",
            "has a total defense advantage",
        ];

        $protectionEval = new ProtectionEval($this->board);

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id !== Piece::K) {
                if ($piece->attacking()) {
                    $diffPhrases = [];
                    $clone = $this->board->clone();
                    $clone->detach($clone->pieceBySq($piece->sq));
                    $clone->refresh();
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
                        $this->elaborate($piece, $diffPhrases);
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    /*
     * Elaborate on the evaluation.
     *
     * @param \Chess\Variant\AbstractPiece $piece
     * @param array $diffPhrases
     */
    private function elaborate(AbstractPiece $piece, array $diffPhrases): void
    {
        $phrase = PiecePhrase::create($piece);
        $phrase = "If $phrase moved, ";
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
    }
}
