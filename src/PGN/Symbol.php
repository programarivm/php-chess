<?php

namespace Chess\PGN;

/**
 * Symbols in PGN format.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Symbol
{
    const WHITE = 'w';
    const BLACK = 'b';

    const BISHOP = 'B';
    const KING = 'K';
    const KNIGHT = 'N';
    const PAWN = 'P';
    const QUEEN = 'Q';
    const ROOK = 'R';

    const CASTLE_SHORT = 'O-O';
    const CASTLE_LONG = 'O-O-O';
    const SQUARE = '[a-h]{1}[1-8]{1}';
    const CHECK = '[\+\#]{0,1}';

    const RESULT_WHITE_WINS = '1-0';
    const RESULT_BLACK_WINS = '0-1';
    const RESULT_DRAW = '1/2-1/2';
    const RESULT_UNKNOWN = '*';
}
