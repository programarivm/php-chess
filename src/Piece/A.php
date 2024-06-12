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
     * @var \Chess\Piece\B
     */
    private B $bishop;

    /**
     * @var \Chess\Piece\N
     */
    private N $knight;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param array $size
     */
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::A);

        $this->bishop = new B($color, $sq, $square);
        $this->knight = new N($color, $sq, $square);

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
            ...$this->bishop->mobility,
            'knight' => $this->knight->mobility,
        ];

        return $this;
    }
}
