<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Variant\AbstractNotation;

class Termination extends AbstractNotation
{
    const WHITE_WINS = '1-0';
    const BLACK_WINS = '0-1';
    const DRAW = '1/2-1/2';
    const UNKNOWN = '*';
    const WHITE_WINS_EXTENDED_ASCII_150 = '1–0';
    const BLACK_WINDS_EXTENDED_ASCII_150 = '0–1';
    const DRAW_EXTENDED_ASCII_150 = '1/2–1/2';
    const DRAW_EXTENDED_ASCII_150_189 = '½–½';
}
