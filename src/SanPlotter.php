<?php

namespace Chess;

use Chess\Eval\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Color;

/**
 * SAN Plotter
 *
 * Plots the oscillations of an evaluation feature in the time domain.
 */
class SanPlotter
{
    /**
     * Returns the time.
     *
     * @param $f \Chess\Eval\AbstractFunction
     * @param \Chess\Variant\AbstractBoard $board
     * @param string $movetext
     * @param string $name
     * @return array
     */
    public static function time(
        AbstractFunction $f,
        AbstractBoard $board,
        string $movetext,
        string $name
    ): array {
        $time[] = 0;
        $sanPlay = new SanPlay($movetext, $board);
        foreach ($sanPlay->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($board->play($board->turn, $val)) {
                    $item = $f::add($f::evaluate($name, $board));
                    $time[] = $item[Color::W] - $item[Color::B];
                }
            }
        }

        return $f::normalize(-1, 1, $time);
    }
}
