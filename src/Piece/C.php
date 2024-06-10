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
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param Square \Chess\Variant\Capablanca\PGN\AN\Square $square
     */
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::C);

        $rook = new R($color, $sq, $square, RType::PROMOTED);
        $knight = new N($color, $sq, $square);

        $this->mobility($rook, $knight);
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(R $rook, N $knight): AbstractPiece
    {
        $this->mobility = [
            ...$rook->getMobility(),
            'knight' => $knight->getMobility(),
        ];

        return $this;
    }
}
