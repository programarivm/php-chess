<?php

namespace Chess;

use Chess\EvalArray;
use Chess\Function\AbstractFunction;
use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;

/**
 * SAN Extractor
 *
 * Extracts the oscillations of all evaluation features for further data analysis.
 */
class SanExtractor
{
    /**
     * Returns the Steinitz evaluation.
     *
     * @param \Chess\Function\AbstractFunction $f
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
                    $steinitz[] = EvalArray::steinitz($f, $board);
                }
            }
        }

        return $steinitz;
    }

    /**
     * Returns the means.
     *
     * @param \Chess\Function\AbstractFunction $f
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
                    $mean[] = EvalArray::mean($f, $board);
                }
            }
        }

        return $mean;
    }

    /**
     * Returns the standard deviations.
     *
     * @param \Chess\Function\AbstractFunction $f
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
                    $mean = EvalArray::mean($f, $board);
                    if ($mean > 0) {
                        $sd[] = EvalArray::sd($f, $board);
                    } elseif ($mean < 0) {
                        $sd[] = EvalArray::sd($f, $board) * -1;
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
     * @param \Chess\Function\AbstractFunction $f
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
                    $eval[] = EvalArray::normalization($f, $board);
                }
            }
        }

        return $eval;
    }
}
