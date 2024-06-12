<?php

namespace Chess\Eval;

use Chess\Eval\BishopPairEval;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * BadBishopEval
 *
 * A bad bishop is a bishop that is blocked by its own pawns.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class BadBishopEval extends AbstractEval implements
    ExplainEvalInterface,
    InverseEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Bad bishop';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
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

        $bishopPairEval = (new BishopPairEval($board))->getResult();

        if (!$bishopPairEval[Color::W] && !$bishopPairEval[Color::B]) {
            foreach ($this->board->getPieces() as $piece) {
                if ($piece->id === Piece::B) {
                    $this->result[$piece->color] += $this->countBlockingPawns(
                        $piece,
                        $this->board->square->color($piece->sq)
                    );
                }
            }
        }

        $this->explain($this->result);
    }

    /**
     * Counts the number of pawns blocking a bishop.
     *
     * @param \Chess\Piece\AbstractPiece $bishop
     * @param string $sqColor
     * @return int
     */
    private function countBlockingPawns(AbstractPiece $bishop, string $sqColor): int
    {
        $count = 0;
        foreach ($this->board->getPieces() as $piece) {
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
