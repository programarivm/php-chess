<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\ColorPhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Checkmate in one evaluation.
 *
 * The turn is set to the opposite color in a cloned chess board. Then, all
 * legal moves are played in a clone of this cloned chess board to determine if
 * a checkmate can be delivered.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class CheckmateInOneEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Checkmate in one';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "could checkmate in one move",
        ];

        if (
            !$this->board->isCheck() &&
            !$this->board->isMate() &&
            !$this->board->isStalemate()
        ) {
            $cloneA = $this->board->clone();
            $cloneA->turn = $this->board->color->opp($this->board->turn);
            foreach ($cloneA->getPieces($this->board->color->opp($this->board->turn)) as $piece) {
                foreach ($piece->sqs() as $sq) {
                    $cloneB = $cloneA->clone();
                    if ($cloneB->playLan($cloneB->turn, $piece->sq . $sq)) {
                        if ($cloneB->isMate()) {
                            $this->result[$piece->color] = 1;
                            $this->explain($this->result);
                            $this->elaborate($piece, $cloneB->history);
                            break 2;
                        }
                    }
                }
            }
        }
    }

    /**
     * Elaborate on the result.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     * @param array $history
     */
    private function elaborate(AbstractPiece $piece, array $history): void
    {
        $end = end($history);

        $this->elaboration[] = ColorPhrase::sentence($piece->color) . " threatens to play {$end['move']['pgn']} delivering checkmate in one move.";
    }
}
