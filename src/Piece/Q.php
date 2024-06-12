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
     * @var \Chess\Piece\R
     */
    private R $rook;

    /**
     * @var \Chess\Piece\B
     */
    private B $bishop;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param array $size
     */
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::Q);

        $this->rook = new R($color, $sq, $square, RType::SLIDER);
        $this->bishop = new B($color, $sq, $square);

        $this->mobility();
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(): AbstractPiece
    {
        $this->mobility = [
            ...$this->rook->mobility,
            ...$this->bishop->mobility,
        ];

        return $this;
    }
}
