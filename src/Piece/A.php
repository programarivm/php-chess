<?php

namespace Chess\Piece;

use Chess\Piece\AbstractPiece;
use Chess\Piece\CapablancaTrait;
use Chess\Piece\B;
use Chess\Piece\N;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;

/**
 * Archbishop.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class A extends AbstractPiece
{
    use CapablancaTrait;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param Square \Chess\Variant\Capablanca\PGN\AN\Square $square
     */
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::A);

        $bishop = new B($color, $sq, $square);
        $knight = new N($color, $sq, $square);

        $this->mobility($bishop, $knight);
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(B $bishop, N $knight): AbstractPiece
    {
        $this->mobility = [
            ...$bishop->getMobility(),
            'knight' => $knight->getMobility(),
        ];

        return $this;
    }
}
