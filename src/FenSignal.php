<?php

namespace Chess;

use Chess\FenHeuristics;
use Chess\Function\FastFunction;
use Chess\Labeller\GoldbachLabeller;
use Chess\Movetext\SanMovetext;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;

/**
 * FEN Signal
 *
 * A signal encoding multiple discrete ternary oscillations.
 */
class FenSignal
{
    /**
     * Maximum number of moves.
     *
     * @var int
     */
    const MAX_MOVES = 300;

    /**
     * One-dimensional array of integer values.
     *
     * @var array
     */
    public array $signal;

    /**
     * @param string $movetext
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(string $movetext, AbstractBoard $board)
    {
        $sanMovetext = new SanMovetext($board->move, $movetext);

        if (!$sanMovetext->validate()) {
            throw new \InvalidArgumentException();
        }

        if (self::MAX_MOVES < count($sanMovetext->moves)) {
            throw new \InvalidArgumentException();
        }

        foreach ($sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if (!$board->play($board->turn, $val)) {
                    throw new MediaException();
                }
                $this->signal[] = (new GoldbachLabeller())
                    ->label((new FenHeuristics(new FastFunction(), $board))->balance);
            }
        }
    }
}
