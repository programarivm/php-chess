<?php

namespace Chess;

use Chess\EvalArray;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;

/**
 * SAN Decoder
 *
 * Decodes the oscillations of all evaluation features into a chess board.
 */
class SanDecoder
{
    /**
     * Returns a chess board.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @param array $mean
     * @return \Chess\Variant\AbstractBoard
     */
    public static function mean(AbstractFunction $f, AbstractBoard $board, array $mean): AbstractBoard
    {
        for ($i = 1; $i < count($mean); $i++) {
            foreach ($board->pieces($board->turn) as $piece) {
                foreach ($piece->moveSqs() as $sq) {
                    $clone = $board->clone();
                    if ($clone->playLan($clone->turn, "{$piece->sq}$sq")) {
                        if (EvalArray::mean($f, $clone) === $mean[$i]) {
                            $board->playLan($board->turn, "{$piece->sq}$sq");
                            break 2;
                        }
                    }
                }
            }
        }

        return $board;
    }
}
