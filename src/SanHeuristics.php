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
    use SanTrait;

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

        $this->result[] = $this->item(EvalFactory::create(
            $function,
            $name,
            $this->board
        ));

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $this->result[] = $this->item(EvalFactory::create(
                        $function,
                        $name,
                        $this->board
                    ));
                }
            }
        }

        foreach ($this->result as $result) {
            $this->balance[] = $result[Color::W] - $result[Color::B];
        }

        $this->balance = $this->normalize(-1, 1, $this->balance);
    }
}
