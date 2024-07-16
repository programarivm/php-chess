<?php

namespace Chess\Variant\Losing\PGN;

use Chess\Variant\Classical\PGN\Move as ClassicalPgnMove;
use Chess\Variant\Classical\PGN\AN\Check;
use Chess\Variant\Classical\PGN\AN\Square;

class Move extends ClassicalPgnMove
{
    const PAWN_PROMOTES = '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQM]{0,1}' . Check::REGEX;
    const PAWN_CAPTURES_AND_PROMOTES = '[a-h]{1}x' . '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQM]{0,1}' . Check::REGEX;
    const PIECE = '[BRQM]{1}[a-h]{0,1}[1-8]{0,1}' . Square::REGEX . Check::REGEX;
    const PIECE_CAPTURES = '[BRQM]{1}[a-h]{0,1}[1-8]{0,1}x' . Square::REGEX . Check::REGEX;
}
