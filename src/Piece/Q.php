<?php

namespace Chess\Piece;

use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

/**
 * Queen.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class Q extends Slider
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param Square \Chess\Variant\Classical\PGN\AN\Square $square
     */
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::Q);

        $rook = new R($color, $sq, $square, RType::SLIDER);
        $bishop = new B($color, $sq, $square);

        $this->mobility($rook, $bishop);
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(R $rook, B $bishop): AbstractPiece
    {
        $this->mobility = [
            ...$rook->getMobility(),
            ...$bishop->getMobility(),
        ];

        return $this;
    }
}
