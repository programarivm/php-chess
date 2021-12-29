<?php

namespace Chess\PGN;

use Chess\PGN\Symbol;

/**
 * Encodes chess moves in PGN format.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Move
{
    const KING = 'K' . Symbol::SQUARE . Symbol::CHECK;
    const KING_CASTLING_SHORT = Symbol::CASTLING_SHORT . Symbol::CHECK;
    const KING_CASTLING_LONG = Symbol::CASTLING_LONG . Symbol::CHECK;
    const KING_CAPTURES = 'Kx' . Symbol::SQUARE . Symbol::CHECK;
    const PIECE = '[BRQ]{1}[a-h]{0,1}[1-8]{0,1}' . Symbol::SQUARE . Symbol::CHECK;
    const PIECE_CAPTURES = '[BRQ]{1}[a-h]{0,1}[1-8]{0,1}x' . Symbol::SQUARE . Symbol::CHECK;
    const KNIGHT = 'N[a-h]{0,1}[1-8]{0,1}' . Symbol::SQUARE . Symbol::CHECK;
    const KNIGHT_CAPTURES = 'N[a-h]{0,1}[1-8]{0,1}x' . Symbol::SQUARE . Symbol::CHECK;
    const PAWN = Symbol::SQUARE . Symbol::CHECK;
    const PAWN_CAPTURES = '[a-h]{1}x' . Symbol::SQUARE . Symbol::CHECK;
    const PAWN_PROMOTES = '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQ]{0,1}' . Symbol::CHECK;
    const PAWN_CAPTURES_AND_PROMOTES = '[a-h]{1}x' . '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQ]{0,1}' . Symbol::CHECK;
}
