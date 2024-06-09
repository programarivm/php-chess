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

        try {
            $cloneA = unserialize(serialize($this->board));
            $cloneA->setTurn($this->board->getColor()->opp($this->board->getTurn()));
            foreach ($cloneA->getPieces($this->board->getColor()->opp($this->board->getTurn())) as $piece) {
                foreach ($piece->sqs() as $sq) {
                    $cloneB = unserialize(serialize($cloneA));
                    if ($cloneB->playLan($cloneB->getTurn(), $piece->getSq() . $sq)) {
                        if ($cloneB->isMate()) {
                            $this->result[$piece->getColor()] = 1;
                            $this->explain($this->result);
                            $this->elaborate($piece, $cloneB->getHistory());
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

        $this->elaboration[] = ColorPhrase::sentence($piece->getColor()) . " threatens to play {$end->move->pgn} delivering checkmate in one move.";
    }
}
