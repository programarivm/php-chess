<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\ColorPhrase;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class DiscoveredCheckEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Discovered check';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1, 9];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight discovered check advantage",
            "has a moderate discovered check advantage",
            "has a total discovered check advantage",
        ];

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->id !== Piece::K) {
                $king = $this->board->getPiece($piece->oppColor(), Piece::K);
                $clone = $this->board->clone();
                $clone->detach($clone->getPieceBySq($piece->sq));
                $clone->refresh();
                $newKing = $clone->getPiece($piece->oppColor(), Piece::K);
                $diffPieces = $this->board->diffPieces($king->attackingPieces(), $newKing->attackingPieces());
                foreach ($diffPieces as $diffPiece) {
                    if ($diffPiece->color === $piece->color) {
                        $this->result[$piece->color] += self::$value[$piece->id];
                        $this->elaborate($piece);
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);
        $sentence = ColorPhrase::sentence($piece->oppColor());

        $this->elaboration[] = ucfirst("The $sentence king can be put in check as long as $phrase moves out of the way.");
    }
}
