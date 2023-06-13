<?php

namespace Chess\Variant\Capablanca\PGN;

use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\PGN\Move as ClassicalMove;
use Chess\Variant\Classical\PGN\AN\Check;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Move.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
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

    /**
     * Extract squares from a string.
     *
     * @param string $string
     * @return string if the value is valid
     */
    public function extractSqs(string $string): string
    {
        return preg_replace(Square::EXTRACT, '', $string);
    }

    /**
     * Explode squares from a string.
     *
     * @param string $string
     * @return array
     */
    public function explodeSqs(string $string): array
    {
        preg_match_all('/'.Square::REGEX.'/', $string, $matches);

        return $matches[0];
    }
}
