<?php

namespace Chess\PGN;

/**
 * PGN symbols.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Symbol
{
    const WHITE = 'w';
    const BLACK = 'b';

    const B = 'B';
    const K = 'K';
    const N = 'N';
    const P = 'P';
    const Q = 'Q';
    const R = 'R';

    const O_O = 'O-O';
    const O_O_O = 'O-O-O';
    const SQUARE = '[a-h]{1}[1-8]{1}';
    const CHECK = '[\+\#]{0,1}';

    const RESULT_WHITE_WINS = '1-0';
    const RESULT_BLACK_WINS = '0-1';
    const RESULT_DRAW = '1/2-1/2';
    const RESULT_UNKNOWN = '*';
}
