<?php

namespace Chess;

use Chess\Play\SanPlay;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\Move;

/**
 * Equilibrium
 *
 * Think of a chessboard in three-dimensional space so that every time a piece
 * moves, the board tilts to one of its four quadrants: Northwest, Northeast,
 * Southwest, Southeast.
 *
 * A chess game can be obtained from a signal encoding the equilibrium factor φ
 * of a chessboard. Remember, Blab's theorem says: "A chess position can be
 * obtained from the last oscillation of a chessboard."
 * 
 * FEN(n) ≡ Ψ(n)
 */
class Equilibrium
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
     * Equilibrium signal encoding.
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
     * Equilibrium signal decoding.
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
