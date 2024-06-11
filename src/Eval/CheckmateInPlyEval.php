<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Tutor\ColorPhrase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Checkmate in half a move.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class CheckmateInPlyEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Checkmate in a ply';

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
            "could checkmate in half a move",
        ];

        try {
            foreach ($this->board->getPieces($this->board->turn) as $piece) {
                foreach ($piece->sqs() as $sq) {
                    $clone = unserialize(serialize($this->board));
                    if ($clone->playLan($clone->turn, $piece->getSq() . $sq)) {
                        if ($clone->isMate()) {
                            $this->result[$piece->getColor()] = 1;
                            $this->explain($this->result);
                            $this->elaborate($piece, $clone->getHistory());
                            break 2;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // prevents the program from stopping if a checkmated position is evaluated
            $this->result = [
                Color::W => 0,
                Color::B => 0,
            ];

            $this->elaboration = [];
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

        $this->elaboration[] = ColorPhrase::sentence($piece->getColor()) . " threatens to play {$end['move']['pgn']} delivering checkmate in half a move.";
    }
}
