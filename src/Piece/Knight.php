<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;
use Chess\Piece\AbstractPiece;

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
        parent::__construct($color, $sq, Symbol::N);

        $this->setTravel();
    }

    /**
     * Calculates the knight's travel.
     */
    protected function setTravel(): void
    {
        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] + 2;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 2);
            $rank = (int)$this->sq[1] + 1;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 2);
            $rank = (int)$this->sq[1] - 1;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) - 1);
            $rank = (int)$this->sq[1] - 2;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] - 2;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {

            $file = chr(ord($this->sq[0]) + 2);
            $rank = (int)$this->sq[1] - 1;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 2);
            $rank = (int)$this->sq[1] + 1;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

        try {
            $file = chr(ord($this->sq[0]) + 1);
            $rank = (int)$this->sq[1] + 2;
            if (Validate::sq($file.$rank)) {
                $this->travel[] = $file . $rank;
            }
        } catch (UnknownNotationException $e) {

        }

    }

    public function getSqs(): array
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

    public function getDefendedSqs(): array
    {
        $sqs = [];
        foreach ($this->travel as $sq) {
            if (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }
}
