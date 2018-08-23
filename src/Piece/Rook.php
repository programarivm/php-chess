<?php

namespace PGNChess\Piece;

use PGNChess\Exception\PieceTypeException;
use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Validate as PgnValidate;
use PGNChess\Piece\AbstractPiece;
use PGNChess\Piece\Type\RookType;

/**
 * Rook class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Rook extends Slider
{
    /**
     * @var string
     */
    private $type;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $square
     * @param string $type
     * @throws \PGNChess\Exception\PieceTypeException
     */
    public function __construct(string $color, string $square, $type)
    {
        if (!in_array($type, RookType::getChoices())) {
            throw new PieceTypeException(
                "A valid rook type needs to be provided in order to instantiate a rook."
            );
        } else {
            $this->type = $type;
        }

        parent::__construct($color, $square, Symbol::ROOK);

        $this->scope = (object)[
            'up' => [],
            'bottom' => [],
            'left' => [],
            'right' => []
        ];

        $this->scope();
    }

    /**
     * Returns the rook's type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Calculates the rook's scope.
     */
    protected function scope(): void
    {
        // up
        try {
            $file = $this->position[0];
            $rank = (int)$this->position[1] + 1;
            while (PgnValidate::square($file.$rank)) {
                $this->scope->up[] = $file . $rank;
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // down
        try {
            $file = $this->position[0];
            $rank = (int)$this->position[1] - 1;
            while (PgnValidate::square($file.$rank)) {
                $this->scope->bottom[] = $file . $rank;
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // left
        try {
            $file = chr(ord($this->position[0]) - 1);
            $rank = (int)$this->position[1];
            while (PgnValidate::square($file.$rank)) {
                $this->scope->left[] = $file . $rank;
                $file = chr(ord($file) - 1);
            }
        } catch (UnknownNotationException $e) {

        }

        // right
        try {
            $file = chr(ord($this->position[0]) + 1);
            $rank = (int)$this->position[1];
            while (PgnValidate::square($file.$rank)) {
                $this->scope->right[] = $file . $rank;
                $file = chr(ord($file) + 1);
            }
        } catch (UnknownNotationException $e) {

        }
    }
}
