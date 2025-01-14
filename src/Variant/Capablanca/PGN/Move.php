<?php

namespace Chess\Variant\Capablanca\PGN;

use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\Classical\PGN\Move as ClassicalMove;
use Chess\Variant\Classical\PGN\Check;
use Chess\Variant\Classical\PGN\Color;

class Move extends ClassicalMove
{
    const KING = 'K' . Square::REGEX . Check::REGEX;
    const KING_CAPTURES = 'Kx' . Square::REGEX . Check::REGEX;
    const KNIGHT = 'N[a-j]{0,1}[1-8]{0,1}' . Square::REGEX . Check::REGEX;
    const KNIGHT_CAPTURES = 'N[a-j]{0,1}[1-8]{0,1}x' . Square::REGEX . Check::REGEX;
    const PAWN = Square::REGEX . Check::REGEX;
    const PAWN_CAPTURES = '[a-j]{1}x' . Square::REGEX . Check::REGEX;
    const PAWN_PROMOTES = '[a-j]{1}(1|8){1}' . '[=]{0,1}[NBRQAC]{0,1}' . Check::REGEX;
    const PAWN_CAPTURES_AND_PROMOTES = '[a-j]{1}x' . '[a-j]{1}(1|8){1}' . '[=]{0,1}[NBRQAC]{0,1}' . Check::REGEX;
    const PIECE = '[ABCRQ]{1}[a-j]{0,1}[1-8]{0,1}' . Square::REGEX . Check::REGEX;
    const PIECE_CAPTURES = '[ABCRQ]{1}[a-j]{0,1}[1-8]{0,1}x' . Square::REGEX . Check::REGEX;

    public function extractSqs(string $string): string
    {
        return preg_replace(Square::EXTRACT, '', $string);
    }

    public function explodeSqs(string $string): array
    {
        preg_match_all('/' . Square::REGEX . '/', $string, $matches);

        return $matches[0];
    }
}
