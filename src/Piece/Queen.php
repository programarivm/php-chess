<?php
namespace Chess\Piece;

use Chess\PGN\Symbol;
use Chess\Piece\AbstractPiece;
use Chess\Piece\Rook;
use Chess\Piece\Bishop;
use Chess\Piece\Type\RookType;

/**
 * Queen class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Queen extends Slider
{
    /**
     * @var \Chess\Piece\Rook
     */
    private $rook;

    /**
     * @var \Chess\Piece\Bishop
     */
    private $bishop;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     */
    public function __construct(string $color, string $sq)
    {
        parent::__construct($color, $sq, Symbol::QUEEN);

        $this->rook = new Rook($color, $sq, RookType::FAKED);
        $this->bishop = new Bishop($color, $sq);

        $this->scope();
    }

    /**
     * Calculates the piece's scope.
     */
    protected function scope(): void
    {
        $this->scope = (object) array_merge(
            (array) $this->rook->getScope(),
            (array) $this->bishop->getScope()
        );
    }
}
