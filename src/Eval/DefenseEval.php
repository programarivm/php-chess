<?php

namespace Chess\Eval;

use Chess\Eval\ProtectionEval;
use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Piece;

/*
 * Defense Evaluation
 *
 * This heuristic evaluates the defensive strength of each side by analyzing
 * how the removal of attacking pieces would affect the opponent's protection.
 * A higher score indicates a stronger defensive position.
 */
class DefenseEval extends AbstractEval
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
                    $this->board->detach($piece);
                    $this->board->refresh();
                    $newProtectionEval = new ProtectionEval($this->board);
                    $diffResult = $newProtectionEval->result[$piece->oppColor()]
                        - $protectionEval->result[$piece->oppColor()];
                    if ($diffResult > 0) {
                        foreach ($newProtectionEval->elaborate() as $key => $val) {
                            if (!in_array($val, $protectionEval->elaborate())) {
                                $diffPhrases[] = $val;
                            }
                        }
                        $this->result[$piece->oppColor()] += round($diffResult, 4);
                        $this->toElaborate[] = [
                            $piece,
                            $diffPhrases,
                        ];
                    }
                    $this->board->attach($piece);
                    $this->board->refresh();
                }
            }
        }
    }

    /**
     * Elaborate on the evaluation.
     *
     * @return array
     */
    public function elaborate(): array
    {
        foreach ($this->toElaborate as $val) {
            $phrase = PiecePhrase::create($val[0]);
            $phrase = "If $phrase moved, ";
            $count = count($val[1]);
            if ($count === 1) {
                $diffPhrase = mb_strtolower($val[1][0]);
                $rephrase = str_replace('is unprotected', 'may well be exposed to attack', $diffPhrase);
                $phrase .= $rephrase;
            } elseif ($count > 1) {
                $phrase .= 'these pieces may well be exposed to attack: ';
                $rephrase = '';
                foreach ($val[1] as $diffPhrase) {
                    $rephrase .= str_replace(' is unprotected.', ', ', $diffPhrase);
                }
                $phrase .= $rephrase;
                $phrase = str_replace(', The', ', the', $phrase);
                $phrase = substr_replace(trim($phrase), '.', -1);
            }
            $this->elaboration[] = $phrase;
        }

        return $this->elaboration;
    }
}
