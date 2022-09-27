<?php

namespace Chess\Variant\Capablanca\Piece;

use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Classical\Piece\AbstractPiece;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\R;
use Chess\Variant\Classical\Piece\RType;
use Chess\Variant\Classical\Piece\Slider;

/**
 * Chancellor.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class C extends Slider
{
    /**
     * @var \Chess\Variant\Classical\Piece\R
     */
    private R $rook;

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
        parent::__construct($color, $sq, Piece::C);

        $this->rook = new R($color, $sq, RType::PROMOTED);
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
            ... (array) $this->rook->getMobility(),
            'knight' => (array) $this->knight->getMobility()
        ];

        return $this;
    }
}
