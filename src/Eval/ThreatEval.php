<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;

class ThreatEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Threat';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1, 5];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight threat advantage",
            "has a moderate threat advantage",
            "has a total threat advantage",
        ];

        foreach ($this->board->getPieces() as $piece) {
            $sq = $piece->getSq();
            $attackingPieces = $piece->attackingPieces();
            $clone = unserialize(serialize($this->board));
            $clone->setTurn($piece->oppColor());

            do {
                if ($attackingPiece = current($attackingPieces)) {
                    $capturedPiece = $clone->getPieceBySq($sq);
                    if ($clone->playLan($clone->getTurn(), $attackingPiece->getSq() . $sq)) {
                        $this->result[$attackingPiece->getColor()] += self::$value[$capturedPiece->getId()];
                        if ($defendingPiece = current($piece->defendingPieces())) {
                            $capturedPiece = $clone->getPieceBySq($sq);
                            if ($clone->playLan($clone->getTurn(), $defendingPiece->getSq() . $sq)) {
                                $this->result[$defendingPiece->getColor()] += self::$value[$capturedPiece->getId()];
                            }
                        }
                        $attackingPieces = $clone->getPieceBySq($sq)->attackingPieces();
                    }
                }
            } while ($attackingPieces);
        }

        $this->explain($this->result);
    }
}
