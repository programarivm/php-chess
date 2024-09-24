<?php

namespace Chess\Eval;

use Chess\Variant\AbstractBoard;
use Chess\Variant\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Back-Rank Checkmate Evaluation
 *
 * A back-rank checkmate is a checkmate delivered by a rook or queen along the
 * opponent's back rank. The mated king is unable to move up the board because
 * it is blocked by friendly pawns on the second rank.
 */
class BackRankCheckmateEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    /**
     * The name of the heuristic.
     *
     * @var string
     */
    const NAME = 'Back-rank checkmate';

    /**
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(AbstractBoard $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'The white player',
            'The black player',
        ];

        $this->observation = [
            "has a back-rank checkmate advantage",
        ];

        $wKing = $this->board->piece(Color::W, Piece::K);
        $bKing = $this->board->piece(Color::B, Piece::K);

        if ($this->isOnBackRank($bKing) &&
            $this->isBlocked($bKing) &&
            $this->isDeliverable($bKing)
        ) {
            $this->result[Color::W] = 1;
        }

        if ($this->isOnBackRank($wKing) &&
            $this->isBlocked($wKing) &&
            $this->isDeliverable($wKing)
        ) {
            $this->result[Color::B] = 1;
        }

        $this->explain($this->result);
    }

    /**
     * Returns true if the king is on a back-rank.
     *
     * @param \Chess\Variant\AbstractPiece $king
     * @return bool
     */
    private function isOnBackRank(AbstractPiece $king): bool
    {
        if ($king->color === Color::W) {
            return $king->rank() === 1;
        }

        return $king->rank() === $this->board->square::SIZE['ranks'];
    }

    /**
     * Returns true if the king is blocked by friendly pawns.
     *
     * @param \Chess\Variant\AbstractPiece $king
     * @return bool
     */
    private function isBlocked(AbstractPiece $king): bool
    {
        if ($this->isOnCorner($king)) {
            return $this->countBlockingPawns($king) === 2;
        }

        return $this->countBlockingPawns($king) === 3;
    }

    /**
     * Returns true if the king is on a corner square.
     *
     * @param \Chess\Variant\AbstractPiece $king
     * @return bool
     */
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

    /**
     * Returns the number of blocking pawns.
     *
     * @param \Chess\Variant\AbstractPiece $king
     * @return int
     */
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

    /**
     * Returns true if there is a rook or queen on the board.
     *
     * @param \Chess\Variant\AbstractPiece $king
     * @return bool
     */
    private function isDeliverable(AbstractPiece $king): bool
    {
        foreach ($this->board->pieces($king->oppColor()) as $piece) {
            if ($piece->id === Piece::R || $piece->id === Piece::Q) {
                return true;
            }
        }

        return false;
    }
}
