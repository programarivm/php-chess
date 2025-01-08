<?php

namespace Chess;

use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;

/**
 * SAN Signal
 *
 * Analog chess data signal.
 *
 * An entire chess game can be obtained from an analog data signal. The
 * precondition for this to be the case is that each position can be transformed
 * into a unique number.
 *
 * You may want to think of a chessboard in three-dimensional space so that
 * every time a piece is moved, the board tilts to one of its four quadrants:
 * Northwest, Northeast, Southwest, Southeast. An angle will be formed that can
 * then be used to encode the signal.
 *
 * Or more simply, the FEN string can be hashed as a unique number.
 */
class SanSignal
{
    /**
     * Converts a chess board into a unique number.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @return int
     */
    public static function phi(AbstractBoard $board): int
    {
        return intval(hash('crc32b', $board->toFen()), 16);
    }

    /**
     * Signal encoding.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @param string $movetext
     * @return array
     */
    public static function encode(AbstractBoard $board, string $movetext): array
    {
        $phi[] = 0;
        $sanPlay = new SanPlay($movetext, $board);
        foreach ($sanPlay->sanMovetext->moves as $val) {
            if ($val !== Move::ELLIPSIS) {
                if ($board->play($board->turn, $val)) {
                    $phi[] = self::phi($board);
                }
            }
        }

        return $phi;
    }

    /**
     * Signal decoding.
     *
     * @param \Chess\Variant\AbstractBoard $board
     * @param array $phi
     * @return \Chess\Variant\AbstractBoard
     */
    public static function decode(AbstractBoard $board, array $phi): AbstractBoard
    {
        for ($i = 1; $i < count($phi); $i++) {
            foreach ($board->pieces($board->turn) as $piece) {
                foreach ($piece->moveSqs() as $sq) {
                    $clone = $board->clone();
                    if ($clone->playLan($clone->turn, "{$piece->sq}$sq")) {
                        if (self::phi($clone) === $phi[$i]) {
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
