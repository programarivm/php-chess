<?php

namespace Chess\Piece;

use Chess\Piece\AbstractPiece;
use Chess\Piece\CapablancaTrait;
use Chess\Piece\N;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Capablanca\PGN\AN\Piece;

/**
 * Chancellor.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class C extends AbstractPiece
{
    use CapablancaTrait;

    /**
     * @var \Chess\Piece\R
     */
    private R $rook;

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
        parent::__construct($color, $sq, $size, Piece::C);

        $this->rook = new R($color, $sq, $size, RType::PROMOTED);
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
            ... (array) $this->rook->getMobility(),
            'knight' => (array) $this->knight->getMobility()
        ];

        return $this;
    }
}
