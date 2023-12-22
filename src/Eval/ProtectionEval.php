<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Protection evaluation.
 *
 * Total piece value obtained from the squares that are not being defended.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class ProtectionEval extends AbstractEval
{
    const NAME = 'Protection';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        foreach ($this->board->getPieces() as $piece) {
            foreach ($piece->attackedPieces() as $attackedPiece) {
                if ($attackedPiece->getId() !== Piece::K) {
                    if (empty($attackedPiece->defendingPieces())) {
                        $this->result[$attackedPiece->oppColor()] += self::$value[$attackedPiece->getId()];
                        $this->explain($attackedPiece);
                    }
                }
            }
        }
    }

    /**
     * Explain the result.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     */
    private function explain(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);
        $phrase = ucfirst("$phrase is unprotected.");
        if (!in_array($phrase, $this->phrases)) {
            $this->phrases[] = $phrase;
        }
    }
}
