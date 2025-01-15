<?php

namespace Chess;

use Chess\Eval\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;

/**
 * SAN Extractor
 *
 * Extracts oscillations data for further analysis.
 */
class SanExtractor
{
    /**
     * Returns the Steinitz evaluation.
     *
     * @param \Chess\Eval\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @param string $movetext
     * @return array
     */
    public static function steinitz(AbstractFunction $f, AbstractBoard $board, string $movetext): array
    {
        $steinitz[] = 0;
        $sanPlay = new SanPlay($movetext, $board);
        foreach ($sanPlay->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($board->play($board->turn, $val)) {
                    $steinitz[] = $f::steinitz($board);
                }
            }
        }

        return $steinitz;
    }

    /**
     * Returns the means.
     *
     * @param \Chess\Eval\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @param string $movetext
     * @return array
     */
    public static function mean(AbstractFunction $f, AbstractBoard $board, string $movetext): array
    {
        $mean[] = 0;
        $sanPlay = new SanPlay($movetext, $board);
        foreach ($sanPlay->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($board->play($board->turn, $val)) {
                    $mean[] = $f::mean($board);
                }
            }
        }

        return $mean;
    }

    /**
     * Returns the standard deviations.
     *
     * @param \Chess\Eval\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @param string $movetext
     * @return array
     */
    public static function sd(AbstractFunction $f, AbstractBoard $board, string $movetext): array
    {
        $sd[] = 0;
        $sanPlay = new SanPlay($movetext, $board);
        foreach ($sanPlay->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($board->play($board->turn, $val)) {
                    $mean = $f::mean($board);
                    if ($mean > 0) {
                        $sd[] = $f::sd($board);
                    } elseif ($mean < 0) {
                        $sd[] = $f::sd($board) * -1;
                    } else {
                        $sd[] = 0;
                    }
                }
            }
        }

        return $sd;
    }

    /**
     * Returns the evaluation arrays.
     *
     * @param \Chess\Eval\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @param string $movetext
     * @return array
     */
    public static function eval(AbstractFunction $f, AbstractBoard $board, string $movetext): array
    {
        $eval[] = array_fill(0, count($f->names()), 0);
        $sanPlay = new SanPlay($movetext, $board);
        foreach ($sanPlay->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($board->play($board->turn, $val)) {
                    $eval[] = $f::normalization($board);
                }
            }
        }

        return $eval;
    }
}
