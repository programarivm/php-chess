<?php

namespace Chess\Eval;

use Chess\Eval\BishopPairEval;
use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\Color;
use Chess\Variant\Classical\PGN\Piece;

/**
 * Bad Bishop Evaluation
 *
 * A bishop that is on the same color as most of own pawns.
 */
class BadBishopEval extends AbstractEval implements InverseEvalInterface
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Bad bishop';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1, 5];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            'has a bishop which is not too good because a few of its pawns are blocking it',
            'has a bad bishop because too many of its pawns are blocking it',
        ];

        $bishopPairEval = (new BishopPairEval($board))->result;

        if (!$bishopPairEval[Color::W] && !$bishopPairEval[Color::B]) {
            foreach ($this->board->pieces() as $piece) {
                if ($piece->id === Piece::B) {
                    $this->result[$piece->color] += $this->countBlockingPawns(
                        $piece,
                        $this->board->square->color($piece->sq)
                    );
                }
            }
        }
    }

    /**
     * Counts the number of blocking pawns.
     *
     * @param \Chess\Variant\AbstractPiece $bishop
     * @param string $sqColor
     * @return bool
     */
    private function countBlockingPawns(AbstractPiece $bishop, string $sqColor): int
    {
        $count = 0;
        foreach ($this->board->pieces() as $piece) {
            if ($piece->id === Piece::P) {
                if (
                    $piece->color === $bishop->color &&
                    $this->board->square->color($piece->sq) === $sqColor
                ) {
                    $count += 1;
                }
            }
        }

        return $count;
    }
}
