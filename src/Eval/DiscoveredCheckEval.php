<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\ColorPhrase;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

class DiscoveredCheckEval extends AbstractEval
{
    const NAME = 'Discovered check';

    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Piece::K) {
                $clone = unserialize(serialize($this->board));
                $movingPiece = $clone->getPieceBySq($piece->getSq());
                $clone->detach($movingPiece);
                $clone->refresh();
                $checkingPieces = $this->board->checkingPieces();
                $newCheckingPieces = $clone->checkingPieces();
                $diffPieces = $this->diffPieces($checkingPieces, $newCheckingPieces);
                foreach ($diffPieces as $diffPiece) {
                    if ($diffPiece->getColor() === $movingPiece->getColor()) {
                        $this->result[$movingPiece->getColor()] += self::$value[$movingPiece->getId()];
                        $this->explain($movingPiece);
                    }
                }
            }
        }
    }

    private function explain(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);
        $sentence = ColorPhrase::sentence($piece->oppColor());

        $this->phrases[] = ucfirst("The $sentence king can be put in check as long as $phrase moves out of the way.");
    }
}
