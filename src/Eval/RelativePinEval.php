<?php

namespace Chess\Eval;

use Chess\Eval\PressureEval;
use Chess\Piece\AbstractPiece;
use Chess\Tutor\PiecePhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Relative pin evaluation.
 *
 * Pieces are removed from a cloned chess board to determine if they are pinned
 * relatively. If so, when removed from the board, the attacking piece will be
 * pressuring a new square containing a more valuable piece.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class RelativePinEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Relative pin';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1, 6.8];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight relative pin advantage",
            "has a moderate relative pin advantage",
            "has a total relative pin advantage",
        ];

        $pressureEval = (new PressureEval($this->board))->getResult();

        foreach ($this->board->getPieces() as $piece) {
            if ($piece->getId() !== Piece::K && $piece->getId() !== Piece::Q) {
                if (!$piece->isPinned()) {
                    $attackingPieces = $piece->attackingPieces($pinned = false);
                    $clone = unserialize(serialize($this->board));
                    $clone->detach($clone->getPieceBySq($piece->getSq()));
                    $clone->refresh();
                    $newPressureEval = (new PressureEval($clone))->getResult();
                    $arrayDiff = array_diff(
                        $newPressureEval[$piece->oppColor()],
                        $pressureEval[$piece->oppColor()]
                    );
                    foreach ($arrayDiff as $sq) {
                        foreach ($clone->getPieceBySq($sq)->attackingPieces($pinned = false) as $newAttackingPiece) {
                            foreach ($attackingPieces as $attackingPiece) {
                                if ($newAttackingPiece->getSq() === $attackingPiece->getSq()) {
                                    $valDiff = abs(self::$value[$attackingPiece->getId()] -
                                        self::$value[$clone->getPieceBySq($sq)->getId()]);
                                    if ($valDiff > 0) {
                                        $this->result[$piece->oppColor()] += round($valDiff, 2);
                                        $this->elaborate($piece);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    /**
     * Elaborate on the result.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $phrase = PiecePhrase::create($piece);

        $this->elaboration[] = ucfirst("$phrase is pinned shielding a piece that is more valuable than the attacking piece.");
    }
}
