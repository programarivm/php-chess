<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

/**
 * Rook.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class R extends Slider
{
    /**
     * @var string
     */
    private string $type;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param Square \Chess\Variant\Classical\PGN\AN\Square $square
     * @param string $type
     */
    public function __construct(string $color, string $sq, Square $square, string $type)
    {
        parent::__construct($color, $sq, $square, Piece::R);

        $this->type = $type;

        $this->mobility = [
            'up' => [],
            'down' => [],
            'left' => [],
            'right' => []
        ];

        $this->mobility();
    }

    /**
     * Returns the rook's type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(): AbstractPiece
    {
        try {
            $file = $this->sq[0];
            $rank = $this->rank() + 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility['up'][] = $file . $rank;
                $rank = (int)$rank + 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = $this->sq[0];
            $rank = $this->rank() - 1;
            while ($this->square->validate($file . $rank)) {
                $this->mobility['down'][] = $file . $rank;
                $rank = (int)$rank - 1;
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = $this->rank();
            while ($this->square->validate($file . $rank)) {
                $this->mobility['left'][] = $file . $rank;
                $file = chr(ord($file) - 1);
            }
        } catch (UnknownNotationException $e) {
        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = $this->rank();
            while ($this->square->validate($file . $rank)) {
                $this->mobility['right'][] = $file . $rank;
                $file = chr(ord($file) + 1);
            }
        } catch (UnknownNotationException $e) {
        }

        return $this;
    }
}
