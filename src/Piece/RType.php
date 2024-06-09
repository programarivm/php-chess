<?php

namespace Chess\Piece;

use Chess\Variant\Classical\PGN\AN\Castle;

/**
 * Rook type.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class RType
{
    const CASTLE_SHORT = Castle::SHORT;
    const CASTLE_LONG = Castle::LONG;
    const PROMOTED = 'promoted';
    const SLIDER = 'slider';
}
