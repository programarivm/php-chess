<?php

namespace Chess\Eval;

use Chess\Tutor\PiecePhrase;
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
class BackRankEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
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
        if ($king->color === Color::W && $king->rank() === 1) {
            return true;
        } elseif ($king->color === Color::B && $king->rank() === $this->board->square::SIZE['ranks']) {
            return true;
        }

        return false;
    }

    private function isBlocked(AbstractPiece $king): bool
    {
        if ($king->color === Color::W) {
            // TODO
        } elseif ($king->color === Color::B) {
            // TODO
        }

        return false;
    }

    private function elaborate(AbstractPiece $king): void
    {
        $phrase = PiecePhrase::create($king);
        $this->elaboration[] = ucfirst("$phrase is vulnerable to back-rank checkmate.");
    }
}
