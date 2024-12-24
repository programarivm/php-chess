<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SAN Heuristics
 *
 * Continuous oscillations in terms of heuristic evaluation features.
 */
class SanHeuristics extends SanPlay
{
    /**
     * The balanced result.
     *
     * @var array
     */
    public array $balance = [];

    /**
     * @param \Chess\Function\AbstractFunction $function
     * @param string $movetext
     * @param string $name
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(
        AbstractFunction $function,
        string $movetext = '',
        string $name = '',
        AbstractBoard $board = null
    ) {
        parent::__construct($movetext, $board);

        $result = [];

        $result[] = EvalGuess::item(
            EvalFactory::create($function, $name, $this->board)
        );

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $result[] = EvalGuess::item(
                        EvalFactory::create($function, $name, $this->board)
                    );
                }
            }
        }

        foreach ($result as $val) {
            $this->balance[] = $val[Color::W] - $val[Color::B];
        }

        $this->balance = EvalGuess::normalize(-1, 1, $this->balance);
    }
}
