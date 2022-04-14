<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\AN\Square;
use Chess\PGN\AN\Piece;

/**
 * Knight class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Knight extends AbstractPiece
{
    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     */
    public function __construct(string $color, string $sq)
    {
        parent::__construct($color, $sq, Piece::N);

        $this->setTravel();
    }

    /**
     * Gets the defended squares.
     *
     * @return mixed array|null
     */
    public function getDefendedSqs(): ?array
    {
        $sqs = [];
        foreach ($this->travel as $sq) {
            if (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    /**
     * Gets the squares where the piece can be placed on.
     *
     * @return mixed array|null
     */
    public function getSqs(): ?array
    {
        $moves = [];
        foreach ($this->travel as $sq) {
            if (in_array($sq, $this->board->getSqEval()->free)) {
                $moves[] = $sq;
            } elseif (in_array($sq, $this->board->getSqEval()->used->{$this->getOppColor()})) {
                $moves[] = $sq;
            }
        }

        return $moves;
    }

    /**
     * Calculates the knight's travel.
     */
    protected function setTravel(): void
    {
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] + 2;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 2);
            $rank = (int)$this->sq[1] + 1;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 2);
            $rank = (int)$this->sq[1] - 1;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] - 2;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] - 2;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {

            $file = chr(ord($this->sq[0]) + 2);
            $rank = (int)$this->sq[1] - 1;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 2);
            $rank = (int)$this->sq[1] + 1;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] + 2;
            if (Square::validate($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }
    }
}
