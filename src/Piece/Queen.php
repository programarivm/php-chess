<?php
namespace PGNChess\Piece;

use PGNChess\PGN\Symbol;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Rook;
use PGNChess\Piece\Bishop;
use PGNChess\Piece\Type\RookType;

/**
 * Queen class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Queen extends Slider
{
    /**
     * @var \PGNChess\Piece\Rook
     */
    private $rook;

    /**
     * @var \PGNChess\Piece\Bishop
     */
    private $bishop;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     */
    public function __construct(string $color, string $square)
    {
        parent::__construct($color, $square, Symbol::QUEEN);

        $this->rook = new Rook($color, $square, RookType::FAKED);
        $this->bishop = new Bishop($color, $square);

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
