<?php

namespace Chess\PGN\AN;

use Chess\PGN\AbstractNotation;

/**
 * Termination.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Termination extends AbstractNotation
{
    const WHITE_WINS = '1-0';
    const BLACK_WINS = '0-1';
    const DRAW = '1/2-1/2';
    const UNKNOWN = '*';
    const WHITE_WINS_EXTENDED_ASCII = '1–0';
    const BLACK_WINDS_EXTENDED_ASCII = '0–1';
    const DRAW_EXTENDED_ASCII = '½–½';
}
