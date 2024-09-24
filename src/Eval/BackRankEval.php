<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Back-Rank Evaluation
 *
 * A back-rank checkmate is a checkmate delivered by a rook or queen along the
 * opponent's back rank. The mated king is unable to move up the board because
 * it is blocked by friendly pawns on the second rank.
 */
class BackRankEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'BackRank';

    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'The white player',
            'The black player',
        ];

        $this->observation = [
            "has a back-rank advantage",
        ];

        $wKing = $this->board->piece(Color::W, Piece::K);
        $bKing = $this->board->piece(Color::B, Piece::K);

        $this->result = [
            Color::W => (int) ($this->isOnBackRank($bKing) && $this->isBlocked($bKing)),
            Color::B => (int) ($this->isOnBackRank($wKing) && $this->isBlocked($wKing)),
        ];

        $this->explain($this->result);
    }

    private function isOnBackRank(AbstractPiece $king): bool
    {
        if ($king->color === Color::W) {
            return $king->rank() === 1;
        }

        return $king->rank() === $this->board->square::SIZE['ranks'];
    }

    private function isBlocked(AbstractPiece $king): bool
    {
        if ($this->isOnCorner($king)) {
            return $this->countBlockingPawns($king) === 2;
        }

        return $this->countBlockingPawns($king) === 3;
    }

    private function isOnCorner(AbstractPiece $king): bool
    {
        if ($king->color === Color::W) {
            return
                $king->sq === $this->board->square->toAlgebraic(
                    0,
                    0
                ) ||
                $king->sq === $this->board->square->toAlgebraic(
                    $this->board->square::SIZE['files'] - 1,
                    0
                );
        }

        return
            $king->sq === $this->board->square->toAlgebraic(
                0,
                $this->board->square::SIZE['ranks'] - 1
            ) ||
            $king->sq === $this->board->square->toAlgebraic(
                $this->board->square::SIZE['files'] - 1,
                $this->board->square::SIZE['ranks'] - 1
            );
    }

    private function countBlockingPawns(AbstractPiece $king): int
    {
        $count = 0;
        foreach ($king->defended() as $defended) {
            if ($defended->id === Piece::P) {
                $count += 1;
            }
        }

        return $count;
    }
}
