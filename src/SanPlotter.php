<?php

namespace Chess;

use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * SAN Plotter
 *
 * Given a PGN movetext in SAN format, this class plots the oscillations of an
 * evaluation feature in the time domain. The data is plotted in a way that is
 * easy for chess players to understand and learn.
 */
class SanPlotter extends SanPlay
{
    /**
     * Time domain.
     *
     * @var array
     */
    public array $time = [
        0,
    ];

    /**
     * @param \Chess\Function\AbstractFunction $f
     * @param string $movetext
     * @param string $name
     * @param \Chess\Variant\AbstractBoard $board
     */
    public function __construct(
        AbstractFunction $f,
        string $movetext = '',
        string $name = '',
        AbstractBoard $board = null
    ) {
        parent::__construct($movetext, $board);

        $result[] = EvalArray::add(EvalFactory::create($f, $name, $this->board));

        foreach ($this->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($this->board->play($this->board->turn, $val)) {
                    $result = EvalArray::add(EvalFactory::create($f, $name, $this->board));
                    $result[] = $result;
                    $this->time[] = $result[Color::W] - $result[Color::B];
                }
            }
        }

        $this->time = EvalArray::normalize(-1, 1, $this->time);
    }
}
