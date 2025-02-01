<?php

namespace Chess\Variant\Capablanca\PGN;

use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\PGN\Move as ClassicalMove;

class Move extends ClassicalMove
{
    const PAWN = Square::AN . self::CHECK;
    const PAWN_CAPTURES = '[a-j]{1}x' . Square::AN . self::CHECK;
    const PAWN_PROMOTES = Square::AN . '[=]{0,1}[NBRQAC]{0,1}' . self::CHECK;
    const PAWN_CAPTURES_AND_PROMOTES = '[a-j]{1}x' . '[a-j]{1}(1|8){1}' . '[=]{0,1}[NBRQAC]{0,1}' . self::CHECK;
    const PIECE = '[ABCKNQR]{1}[a-j]{0,1}[1-8]{0,1}' . Square::AN . self::CHECK;
    const PIECE_CAPTURES = '[ABCKNQR]{1}[a-j]{0,1}[1-8]{0,1}x' . Square::AN . self::CHECK;
}
