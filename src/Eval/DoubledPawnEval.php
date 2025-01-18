<?php

namespace Chess\Eval;

use Chess\Phrase\PiecePhrase;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Doubled Pawn Evaluation
 *
 * A pawn is doubled if there are two pawns of the same color on the same file.
 */
class DoubledPawnEval extends AbstractEval implements InverseEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;
    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Doubled pawn';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has more doubled pawns than its opponent",
        ];

        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P) {
                if ($nextPiece = $this->board->pieceBySq($piece->file() . $piece->nextRank($this->board->square))) {
                    if ($nextPiece->id === Piece::P && $nextPiece->color === $piece->color) {
                        $this->result[$piece->color] += 1;
                        $this->toElaborate[] = $nextPiece;
                    }
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
            $phrase = PiecePhrase::create($val);
            $this->elaboration[] = ucfirst("$phrase is doubled.");
        }

        return $this->elaboration;
    }
}
