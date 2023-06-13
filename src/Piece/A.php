<?php

namespace Chess\Piece;

use Chess\Piece\AbstractPiece;
use Chess\Piece\CapablancaTrait;
use Chess\Piece\B;
use Chess\Piece\N;
use Chess\Variant\Capablanca\PGN\AN\Piece;

/**
 * Archbishop.
 *
 * @author Jordi Bassagañas
 * @license GPL
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
    public function __construct(string $color, string $sq, array $size)
    {
        parent::__construct($color, $sq, $size, Piece::A);

        $this->bishop = new B($color, $sq, $size);
        $this->knight = new N($color, $sq, $size);

        $this->mobility();
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(): AbstractPiece
    {
        $this->mobility = (object) [
            ... (array) $this->bishop->getMobility(),
            'knight' => (array) $this->knight->getMobility()
        ];

        return $this;
    }
}
