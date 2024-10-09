<?php

namespace Chess\Eval;

use Chess\Tutor\ColorPhrase;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * The DiscoveredCheckEval class evaluates the advantage gained by discovered checks on the board.
 * A discovered check occurs when a piece moves out of the way, allowing a different piece to check the opponent's king.
 */
class DiscoveredCheckEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    // The name of this evaluation heuristic.
    const NAME = 'Discovered check';

    /**
     * Constructor initializes the board and sets up evaluation parameters.
     *
     * @param AbstractBoard $board The chess board to evaluate.
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        // Range of evaluation values.
        $this->range = [1, 9];

        // Subjects of the evaluation.
        $this->subject = [
            'White',
            'Black',
        ];

        // Observations based on the degree of discovered check advantage.
        $this->observation = [
            "has a slight discovered check advantage",
            "has a moderate discovered check advantage",
            "has a total discovered check advantage",
        ];

        // Iterate over each piece on the board.
        foreach ($this->board->pieces() as $piece) {
            // Skip if the piece is a king.
            if ($piece->id !== Piece::K) {
                // Get the opponent's king and create a clone of the board.
                $king = $this->board->piece($piece->oppColor(), Piece::K);
                $clone = $this->board->clone();
                // Remove the current piece from the cloned board and refresh.
                $clone->detach($clone->pieceBySq($piece->sq));
                $clone->refresh();
                // Get the new position of the opponent's king in the cloned board.
                $newKing = $clone->piece($piece->oppColor(), Piece::K);
                // Evaluate the difference in attacking pieces between the original and cloned boards.
                foreach ($this->board->diffPieces($king->attacking(), $newKing->attacking()) as $diffPiece) {
                    // If the difference is caused by a piece of the same color, update the result and elaborate.
                    if ($diffPiece->color === $piece->color) {
                        $this->result[$piece->color] += self::$value[$piece->id];
                        $this->elaborate($piece);
                    }
                }
            }
        }

        // Explain the result of the evaluation.
        $this->explain($this->result);
    }

    /**
     * Creates an elaboration phrase for a given piece.
     *
     * @param AbstractPiece $piece The piece to elaborate on.
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);
        $sentence = ColorPhrase::sentence($piece->oppColor());

        $this->elaboration[] = ucfirst("The $sentence king can be put in check as long as $phrase moves out of the way.");
    }
}
