<?php

namespace Chess\Variant\Capablanca\Piece;

use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Classical\Piece\AbstractPiece;
use Chess\Variant\Classical\Piece\B;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\Slider;

/**
 * Archbishop.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class A extends Slider
{
    /**
     * @var \Chess\Variant\Classical\Piece\B
     */
    private B $bishop;

    /**
     * @var \Chess\Variant\Classical\Piece\N
     */
    private N $knight;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     */
    public function __construct(string $color, string $sq)
    {
        parent::__construct($color, $sq, Piece::A);

        $this->bishop = new B($color, $sq);
        $this->knight = new N($color, $sq);

        $this->mobility();
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Variant\Classical\Piece\AbstractPiece
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
