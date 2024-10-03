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


    /*
     * Constructor for the DefenseEval class.
     *

     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        // Set the range of possible evaluation scores
        $this->range = [1, 9];

        // Define the subjects of the evaluation (both colors)
        $this->subject = [
            'White',
            'Black',
        ];

        // Define possible observations based on the evaluation results
        $this->observation = [
            "has a slight defense advantage",
            "has a moderate defense advantage",
            "has a total defense advantage",
        ];

        // Create a ProtectionEval object for the current board state
        $protectionEval = new ProtectionEval($this->board);

        // Evaluate each piece on the board
        foreach ($this->board->pieces() as $piece) {
            // Skip the king
            if ($piece->id !== Piece::K) {
                // Check if the piece is attacking
                if ($piece->attacking()) {
                    $diffPhrases = [];
                    // Create a clone of the board and remove the current piece
                    $clone = $this->board->clone();
                    $clone->detach($clone->pieceBySq($piece->sq));
                    $clone->refresh();
                    // Evaluate protection on the cloned board
                    $newProtectionEval = new ProtectionEval($clone);
                    // Calculate the difference in protection for the opponent's color
                    $diffResult = $newProtectionEval->getResult()[$piece->oppColor()]
                        - $protectionEval->getResult()[$piece->oppColor()];
                    // If there's a positive difference, update the result and elaborate
                    if ($diffResult > 0) {
                        // Collect new protection phrases
                        foreach ($newProtectionEval->getElaboration() as $key => $val) {
                            if (!in_array($val, $protectionEval->getElaboration())) {
                                $diffPhrases[] = $val;
                            }
                        }
                        // Update the result for the opponent's color
                        $this->result[$piece->oppColor()] += round($diffResult, 2);
                        // Elaborate on the defensive impact of the piece
                        $this->elaborate($piece, $diffPhrases);
                    }
                }
            }
        }

        // Explain the overall evaluation results
        $this->explain($this->result);
    }

    /*

     * Elaborate on the defensive impact of a piece.

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