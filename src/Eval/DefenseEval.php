<?php
namespace Chess\Eval;
use Chess\Eval\ProtectionEval;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * DefenseEval class
 * 
 * This class evaluates the defensive strength of a chess position.
 * It considers how removing each piece would affect the opponent's protection score.
 */
class DefenseEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Defense';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;
        $this->range = [1, 9]; // Evaluation range
        $this->subject = [
            'White',
            'Black',
        ];
        $this->observation = [
            "has a slight defense advantage",
            "has a moderate defense advantage",
            "has a total defense advantage",
        ];

        // Create initial protection evaluation
        $protectionEval = new ProtectionEval($this->board);

        // Iterate through all pieces on the board
        foreach ($this->board->pieces() as $piece) {
            if ($piece->id !== Piece::K) { // Skip the king
                if ($piece->attacking()) {
                    $diffPhrases = [];
                    // Create a clone of the board and remove the current piece
                    $clone = $this->board->clone();
                    $clone->detach($clone->pieceBySq($piece->sq));
                    $clone->refresh();

                    // Evaluate protection on the cloned board
                    $newProtectionEval = new ProtectionEval($clone);

                    // Calculate the difference in opponent's protection score
                    $diffResult = $newProtectionEval->getResult()[$piece->oppColor()]
                        - $protectionEval->getResult()[$piece->oppColor()];

                    if ($diffResult > 0) {
                        // Find new unprotected pieces
                        foreach ($newProtectionEval->getElaboration() as $key => $val) {
                            if (!in_array($val, $protectionEval->getElaboration())) {
                                $diffPhrases[] = $val;
                            }
                        }
                        // Update the result for the opponent's color
                        $this->result[$piece->oppColor()] += round($diffResult, 2);
                        // Generate elaboration for this piece
                        $this->elaborate($piece, $diffPhrases);
                    }
                }
            }
        }

        // Generate final explanation
        $this->explain($this->result);
    }

    /**
     * Generate elaboration text for a piece
     * 
     * This method creates a description of what would happen if a piece were moved,
     * focusing on which opponent's pieces would become exposed to attack.
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
