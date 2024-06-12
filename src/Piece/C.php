<?php

namespace Chess\Piece;

use Chess\Piece\AbstractPiece;
use Chess\Piece\CapablancaTrait;
use Chess\Piece\N;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Capablanca\PGN\AN\Square;

/**
 * Chancellor.
 *
 * @author Jordi Bassagaña
 * @license MIT
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
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::C);

        $this->rook = new R($color, $sq, $square, RType::SLIDER);
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
            ...$this->rook->mobility,
            'knight' => $this->knight->mobility,
        ];

        return $this;
    }
}
