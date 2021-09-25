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
     * @param string $square
     */
    public function __construct(string $color, string $square)
    {
        parent::__construct($color, $square, Symbol::PAWN);

        $this->file = $this->position[0];

        switch ($this->color) {

            case Symbol::WHITE:
                $this->ranks = (object) [
                    'initial' => 2,
                    'next' => (int)$this->position[1] + 1,
                    'promotion' => 8
                ];
                break;

            case Symbol::BLACK:
                $this->ranks = (object) [
                    'initial' => 7,
                    'next' => (int)$this->position[1] - 1,
                    'promotion' => 1
                ];
                break;
        }

        $this->captureSquares = [];

        $this->scope = (object)[
            'up' => []
        ];

        $this->scope();
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
    public function getEnPassantSquare()
    {
        return $this->enPassantSquare;
    }

    /**
     * Calculates the pawn's scope.
     */
    protected function scope(): void
    {
        // next rank
        try {
            if (Validate::square($this->file . $this->ranks->next, true)) {
                $this->scope->up[] = $this->file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // two square advance
        if ($this->position[1] == 2 && $this->ranks->initial == 2) {
            $this->scope->up[] = $this->file . ($this->ranks->initial + 2);
        }
        elseif ($this->position[1] == 7 && $this->ranks->initial == 7) {
            $this->scope->up[] = $this->file . ($this->ranks->initial - 2);
        }

        // capture square
        try {
            $file = chr(ord($this->file) - 1);
            if (Validate::square($file.$this->ranks->next, true)) {
                $this->captureSquares[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // capture square
        try {
            $file = chr(ord($this->file) + 1);
            if (Validate::square($file.$this->ranks->next, true)) {
                $this->captureSquares[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }
    }

    public function getLegalMoves(): array
    {
        $moves = [];

        // add up squares
        foreach($this->scope->up as $square) {
            if (in_array($square, $this->boardStatus->squares->free)) {
                $moves[] = $square;
            } else {
                break;
            }
        }

        // add capture squares
        foreach($this->captureSquares as $square) {
            if (in_array($square, $this->boardStatus->squares->used->{$this->getOppColor()})) {
                $moves[] = $square;
            }
        }

        // en passant implementation
        if (isset($this->boardStatus->lastHistoryEntry) &&
            $this->boardStatus->lastHistoryEntry->move->identity === Symbol::PAWN &&
            $this->boardStatus->lastHistoryEntry->move->color === $this->getOppColor()) {
            switch ($this->getColor()) {
                case Symbol::WHITE:
                    if ((int)$this->position[1] === 5) {
                        $captureSquare =
                            $this->boardStatus->lastHistoryEntry->move->position->next[0] .
                            ($this->boardStatus->lastHistoryEntry->move->position->next[1]+1);
                        if (in_array($captureSquare, $this->captureSquares)) {
                            $this->enPassantSquare = $this->boardStatus->lastHistoryEntry->move->position->next;
                            $moves[] = $captureSquare;
                        }
                    }
                    break;
                case Symbol::BLACK:
                    if ((int)$this->position[1] === 4) {
                        $captureSquare =
                            $this->boardStatus->lastHistoryEntry->move->position->next[0] .
                            ($this->boardStatus->lastHistoryEntry->move->position->next[1]-1);
                        if (in_array($captureSquare, $this->captureSquares)) {
                            $this->enPassantSquare = $this->boardStatus->lastHistoryEntry->move->position->next;
                            $moves[] = $captureSquare;
                        }
                    }
                    break;
            }
        }

        return $moves;
    }

    public function getDefendedSquares(): array
    {
        $squares = [];
        foreach($this->captureSquares as $square) {
            if (in_array($square, $this->boardStatus->squares->used->{$this->getColor()})) {
                $squares[] = $square;
            }
        }

        return $squares;
    }

    /**
     * Checks whether the pawn is promoted.
     *
     * @return boolean
     */
    public function isPromoted(): bool
    {
        return isset($this->move->newIdentity) && (int)$this->getMove()->position->next[1] === $this->ranks->promotion;
    }
}
