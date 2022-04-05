<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\Symbol;
use Chess\PGN\Validate;
use Chess\Piece\AbstractPiece;

/**
 * Pawn class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Pawn extends AbstractPiece
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var array
     */
    private $ranks;

    /**
     * @var array
     */
    private $captureSquares;

    /**
     * @var string
     */
    private $enPassantSquare;

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     */
    public function __construct(string $color, string $sq)
    {
        parent::__construct($color, $sq, Symbol::P);

        $this->file = $this->sq[0];

        switch ($this->color) {

            case Symbol::WHITE:
                $this->ranks = (object) [
                    'initial' => 2,
                    'next' => (int)$this->sq[1] + 1,
                    'promotion' => 8
                ];
                break;

            case Symbol::BLACK:
                $this->ranks = (object) [
                    'initial' => 7,
                    'next' => (int)$this->sq[1] - 1,
                    'promotion' => 1
                ];
                break;
        }

        $this->captureSquares = [];

        $this->travel = (object)[
            'up' => []
        ];

        $this->setTravel();
    }

    /**
     * Gets the pawn's file.
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Gets the pawn's rank info.
     *
     * @return stdClass
     */
    public function getRanks(): \stdClass
    {
        return $this->ranks;
    }

    /**
     * Gets the capture squares.
     *
     * @return array
     */
    public function getCaptureSquares(): array
    {
        return $this->captureSquares;
    }

    /**
     * Gets the en passant square.
     *
     * @return string
     */
    public function getEnPassantSq()
    {
        return $this->enPassantSquare;
    }

    /**
     * Calculates the pawn's travel.
     */
    protected function setTravel(): void
    {
        // next rank
        try {
            if (Validate::sq($this->file . $this->ranks->next, true)) {
                $this->travel->up[] = $this->file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // two square advance
        if ($this->sq[1] == 2 && $this->ranks->initial == 2) {
            $this->travel->up[] = $this->file . ($this->ranks->initial + 2);
        }
        elseif ($this->sq[1] == 7 && $this->ranks->initial == 7) {
            $this->travel->up[] = $this->file . ($this->ranks->initial - 2);
        }

        // capture square
        try {
            $file = chr(ord($this->file) - 1);
            if (Validate::sq($file.$this->ranks->next, true)) {
                $this->captureSquares[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // capture square
        try {
            $file = chr(ord($this->file) + 1);
            if (Validate::sq($file.$this->ranks->next, true)) {
                $this->captureSquares[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }
    }

    public function getSqs(): array
    {
        $moves = [];

        // add up squares
        foreach($this->travel->up as $sq) {
            if (in_array($sq, $this->board->getSqEval()->free)) {
                $moves[] = $sq;
            } else {
                break;
            }
        }

        // add capture squares
        foreach($this->captureSquares as $sq) {
            if (in_array($sq, $this->board->getSqEval()->used->{$this->getOppColor()})) {
                $moves[] = $sq;
            }
        }

        // en passant implementation
        if ($this->board->getLastHistory() &&
            $this->board->getLastHistory()->move->id === Symbol::P &&
            $this->board->getLastHistory()->move->color === $this->getOppColor()) {
            switch ($this->getColor()) {
                case Symbol::WHITE:
                    if ((int)$this->sq[1] === 5) {
                        $captureSquare =
                            $this->board->getLastHistory()->move->sq->next[0] .
                            ($this->board->getLastHistory()->move->sq->next[1]+1);
                        if (in_array($captureSquare, $this->captureSquares)) {
                            $this->enPassantSquare = $this->board->getLastHistory()->move->sq->next;
                            $moves[] = $captureSquare;
                        }
                    }
                    break;
                case Symbol::BLACK:
                    if ((int)$this->sq[1] === 4) {
                        $captureSquare =
                            $this->board->getLastHistory()->move->sq->next[0] .
                            ($this->board->getLastHistory()->move->sq->next[1]-1);
                        if (in_array($captureSquare, $this->captureSquares)) {
                            $this->enPassantSquare = $this->board->getLastHistory()->move->sq->next;
                            $moves[] = $captureSquare;
                        }
                    }
                    break;
            }
        }

        return $moves;
    }

    public function getDefendedSqs(): array
    {
        $sqs = [];
        foreach($this->captureSquares as $sq) {
            if (in_array($sq, $this->board->getSqEval()->used->{$this->getColor()})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    /**
     * Checks whether the pawn is promoted.
     *
     * @return boolean
     */
    public function isPromoted(): bool
    {
        return isset($this->move->newId) &&
            (int)$this->getMove()->sq->next[1] === $this->ranks->promotion;
    }
}
