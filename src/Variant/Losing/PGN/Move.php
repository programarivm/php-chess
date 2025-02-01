<?php

namespace Chess\Variant\Losing\PGN;

use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;
use Chess\Variant\Classical\PGN\Square;

class Move extends ClassicalPgnMove
{
    const PAWN_PROMOTES = '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQM]{0,1}' . self::CHECK;
    const PAWN_CAPTURES_AND_PROMOTES = '[a-h]{1}x' . '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQM]{0,1}' . self::CHECK;
    const PIECE = '[BMNQR]{1}[a-h]{0,1}[1-8]{0,1}' . Square::AN . self::CHECK;
    const PIECE_CAPTURES = '[BMNQR]{1}[a-h]{0,1}[1-8]{0,1}x' . Square::AN . self::CHECK;
}
