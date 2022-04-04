<?php

namespace Chess\Piece;

use Chess\Exception\PieceTypeException;
use Chess\Exception\UnknownNotationException;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;
use Chess\Piece\AbstractPiece;
use Chess\Piece\Type\RookType;

/**
 * Rook class.
 *
 * @author Jordi Bassagañas
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
     * @param string $sq
     * @param string $type
     * @throws \Chess\Exception\PieceTypeException
     */
    public function __construct(string $color, string $sq, $type)
    {
        if (!in_array($type, RookType::getChoices())) {
            throw new PieceTypeException(
                "A valid rook type needs to be provided in order to instantiate a rook."
            );
        } else {
            $this->type = $type;
        }

        parent::__construct($color, $sq, Symbol::ROOK);

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
            $file = $this->sq[0];
            $rank = (int)$this->sq[1] + 1;
            while (Validate::sq($file.$rank)) {
                $this->scope->up[] = $file . $rank;
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // down
        try {
            $file = $this->sq[0];
            $rank = (int)$this->sq[1] - 1;
            while (Validate::sq($file.$rank)) {
                $this->scope->bottom[] = $file . $rank;
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {

        }

        // left
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1];
            while (Validate::sq($file.$rank)) {
                $this->scope->left[] = $file . $rank;
                $file = chr(ord($file) - 1);
            }
        } catch (UnknownNotationException $e) {

        }

        // right
        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1];
            while (Validate::sq($file.$rank)) {
                $this->scope->right[] = $file . $rank;
                $file = chr(ord($file) + 1);
            }
        } catch (UnknownNotationException $e) {

        }
    }
}
