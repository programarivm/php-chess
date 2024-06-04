<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
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
class CheckmateInOneEval extends AbstractEval implements ExplainEvalInterface
{
    use ExplainEvalTrait;

    const NAME = 'Checkmate in one';

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [1];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "can checkmate in one move",
        ];

        $cloneA = unserialize(serialize($this->board));
        $cloneA->setTurn(Color::opp($this->board->getTurn()));
        foreach ($cloneA->getPieces(Color::opp($this->board->getTurn())) as $piece) {
            foreach ($piece->sqs() as $sq) {
                $cloneB = unserialize(serialize($cloneA));
                if ($cloneB->playLan($cloneB->getTurn(), $piece->getSq() . $sq)) {
                    if ($cloneB->isMate()) {
                        $this->result[$piece->getColor()] = 1;
                    }
                }
            }
        }

        $this->explain($this->result);
    }
}
