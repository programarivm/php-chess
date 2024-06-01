<?php

namespace Chess\Piece;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Pawn.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class P extends AbstractPiece
{
    /**
     * @var object
     */
    private object $ranks;

    /**
     * @var array
     */
    private array $captureSqs;

    /**
     * @var string
     */
    private string $enPassantSq = '';

    /**
     * Constructor.
     *
     * @param string $color
     * @param string $sq
     * @param array $size
     */
    public function __construct(string $color, string $sq, array $size)
    {
        parent::__construct($color, $sq, $size, Piece::P);

        if ($this->color === Color::W) {
            $this->ranks = (object) [
                'start' => 2,
                'next' => $this->getSqRank() + 1,
                'end' => $this->size['ranks'],
            ];
        } elseif ($this->color === Color::B) {
            $this->ranks = (object) [
                'start' => $this->size['ranks'] - 1,
                'next' => $this->getSqRank() - 1,
                'end' => 1,
            ];
        }

        $this->captureSqs = [];

        $this->mobility = [];

        $this->mobility();
    }

    /**
     * Calculates the piece's mobility.
     *
     * @return \Chess\Piece\AbstractPiece
     */
    protected function mobility(): AbstractPiece
    {
        // next rank
        try {
            if ($this->isValidSq($this->getSqFile().$this->ranks->next)) {
                $this->mobility[] = $this->getSqFile() . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // two square advance
        if ($this->getSqRank() === 2 && $this->ranks->start == 2) {
            $this->mobility[] = $this->getSqFile() . ($this->ranks->start + 2);
        } elseif (
            $this->getSqRank() === $this->size['ranks'] - 1 &&
            $this->ranks->start == $this->size['ranks'] - 1
        ) {
            $this->mobility[] = $this->getSqFile() . ($this->ranks->start - 2);
        }

        // capture square
        try {
            $file = chr(ord($this->getSqFile()) - 1);
            if ($this->isValidSq($file.$this->ranks->next)) {
                $this->captureSqs[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        // capture square
        try {
            $file = chr(ord($this->getSqFile()) + 1);
            if ($this->isValidSq($file.$this->ranks->next)) {
                $this->captureSqs[] = $file . $this->ranks->next;
            }
        } catch (UnknownNotationException $e) {

        }

        return $this;
    }

    /**
     * Returns the piece's legal moves.
     *
     * @return array
     */
    public function sqs(): array
    {
        $sqs = [];

        // mobility squares
        foreach ($this->mobility as $sq) {
            if (in_array($sq, $this->board->getSqCount()->free)) {
                $sqs[] = $sq;
            } else {
                break;
            }
        }

        // capture squares
        foreach ($this->captureSqs as $sq) {
            if (in_array($sq, $this->board->getSqCount()->used->{$this->oppColor()})) {
                $sqs[] = $sq;
            }
        }

        // en passant square
        $history = $this->board->getHistory();
        $end = end($history);
        if ($end && $end->move->id === Piece::P && $end->move->color === $this->oppColor()) {
           if ($this->color === Color::W) {
               if ($this->getSqRank() === $this->size['ranks'] - 3) {
                   $captureSq = $end->move->sq->next[0] . ($this->getSqRank() + 1);
                   if (in_array($captureSq, $this->captureSqs)) {
                        $this->enPassantSq = $captureSq;
                        $sqs[] = $captureSq;
                   }
               }
           } elseif ($this->color === Color::B) {
               if ($this->getSqRank() === 4) {
                   $captureSq = $end->move->sq->next[0] . ($this->getSqRank() - 1);
                   if (in_array($captureSq, $this->captureSqs)) {
                       $this->enPassantSq = $captureSq;
                       $sqs[] = $captureSq;
                   }
               }
           }
        } else {
           $sqs[] = $this->enPassantSq;
        }

        return array_filter(array_unique($sqs));
    }

    /**
     * Returns the squares defended by the piece.
     *
     * @return array|null
     */
    public function defendedSqs(): ?array
    {
        $sqs = [];
        foreach($this->captureSqs as $sq) {
            if (in_array($sq, $this->board->getSqCount()->used->{$this->getColor()})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }

    /**
     * Gets the pawn's rank info.
     *
     * @return object
     */
    public function getRanks(): object
    {
        return $this->ranks;
    }

    /**
     * Gets the capture squares.
     *
     * @return array
     */
    public function getCaptureSqs(): array
    {
        return $this->captureSqs;
    }

    /**
     * Gets the en passant square.
     *
     * @return string
     */
    public function getEnPassantSq(): ?string
    {
        return $this->enPassantSq;
    }

    /**
     * Sets the en passant square.
     *
     * @param string $sq
     * @return \Chess\Piece\P
     */
    public function setEnPassantSq(string $sq): P
    {
        $this->enPassantSq = $sq;

        return $this;
    }

    /**
     * Checks whether the pawn is promoted.
     *
     * @return boolean
     */
    public function isPromoted(): bool
    {
        $rank = (int) substr($this->getMove()->sq->next, 1);

        return isset($this->move->newId) && $rank === $this->ranks->end;
    }

    /**
     * Returns the en passant pawn.
     *
     * @return \Chess\Piece\P|null
     */
    public function enPassantPawn(): ?P
    {
        if ($this->enPassantSq) {
            $rank = (int) substr($this->enPassantSq, 1);
            $this->getColor() === Color::W ? $rank-- : $rank++;
            return $this->board->getPieceBySq($this->enPassantSq[0].$rank);
        }

        return null;
    }
}
