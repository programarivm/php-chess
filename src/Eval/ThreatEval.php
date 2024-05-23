<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;

class ThreatEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Threat';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [0.8, 5];

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
            $threat = [
                Color::W => 0,
                Color::B => 0,
            ];

            do {
                if ($attackingPiece = current($attackingPieces)) {
                    $capturedPiece = $clone->getPieceBySq($sq);
                    if ($clone->playLan($clone->getTurn(), $attackingPiece->getSq() . $sq)) {
                        $threat[$attackingPiece->getColor()] += self::$value[$capturedPiece->getId()];
                        if ($defendingPiece = current($piece->defendingPieces())) {
                            $capturedPiece = $clone->getPieceBySq($sq);
                            if ($clone->playLan($clone->getTurn(), $defendingPiece->getSq() . $sq)) {
                                $threat[$defendingPiece->getColor()] += self::$value[$capturedPiece->getId()];
                            }
                        }
                        $attackingPieces = $clone->getPieceBySq($sq)->attackingPieces();
                    }
                }
            } while ($attackingPieces);

            $diff = $threat[Color::W] - $threat[Color::B];

            if ($piece->oppColor() === Color::W) {
                if ($diff > 0) {
                    $this->result[Color::W] += $diff;
                }
            } else {
                if ($diff < 0) {
                    $this->result[Color::B] += abs($diff);
                }
            }
        }

        $this->explain($this->result);
    }
}
