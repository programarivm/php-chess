<?php

namespace Chess\Piece;

use Chess\Castling;
use Chess\PGN\Symbol;
use Chess\Piece\AbstractPiece;
use Chess\Piece\Rook;
use Chess\Piece\Bishop;
use Chess\Piece\Type\RookType;

/**
 * King class.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class King extends AbstractPiece
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
        parent::__construct($color, $sq, Symbol::KING);

        $this->rook = new Rook($color, $sq, RookType::SLIDER);
        $this->bishop = new Bishop($color, $sq);

        $this->setTravel();
    }

    protected function moveCastlingLong()
    {
        $rule = Castling::color($this->getColor())[Symbol::KING][Symbol::CASTLE_LONG];
        if (!$this->board->getCastling()[$this->getColor()]['isCastled']) {
            if ($this->board->getCastling()[$this->getColor()][Symbol::CASTLE_LONG]) {
                if (
                    in_array($rule['sqs']['b'], $this->board->getSqEval()->free) &&
                    in_array($rule['sqs']['c'], $this->board->getSqEval()->free) &&
                    in_array($rule['sqs']['d'], $this->board->getSqEval()->free) &&
                    !in_array($rule['sqs']['b'], $this->board->getSpaceEval()->{$this->getOppColor()}) &&
                    !in_array($rule['sqs']['c'], $this->board->getSpaceEval()->{$this->getOppColor()}) &&
                    !in_array($rule['sqs']['d'], $this->board->getSpaceEval()->{$this->getOppColor()})
                ) {
                    return $rule['sq']['next'];
                }
            }
        }

        return null;
    }

    protected function moveCastlingShort()
    {
        $rule = Castling::color($this->getColor())[Symbol::KING][Symbol::CASTLE_SHORT];
        if (!$this->board->getCastling()[$this->getColor()]['isCastled']) {
            if ($this->board->getCastling()[$this->getColor()][Symbol::CASTLE_SHORT]) {
                if (
                    in_array($rule['sqs']['f'], $this->board->getSqEval()->free) &&
                    in_array($rule['sqs']['g'], $this->board->getSqEval()->free) &&
                    !in_array($rule['sqs']['f'], $this->board->getSpaceEval()->{$this->getOppColor()}) &&
                    !in_array($rule['sqs']['g'], $this->board->getSpaceEval()->{$this->getOppColor()})
                ) {
                    return $rule['sq']['next'];
                }
            }
        }

        return null;
    }

    protected function movesCaptures()
    {
        $movesCaptures = array_intersect(
            array_values((array)$this->travel),
            $this->board->getSqEval()->used->{$this->getOppColor()}
        );

        return array_diff($movesCaptures, $this->board->getDefenseEval()->{$this->getOppColor()});
    }

    protected function movesKing()
    {
        $movesKing = array_intersect(array_values((array)$this->travel), $this->board->getSqEval()->free);

        return array_diff($movesKing, $this->board->getSpaceEval()->{$this->getOppColor()});
    }

    /**
     * Gets the king's castling rook.
     *
     * @param array $pieces
     * @return mixed \Chess\Piece\Rook|null
     */
    public function getCastlingRook(array $pieces)
    {
        $rule = Castling::color($this->getColor())[Symbol::ROOK];
        foreach ($pieces as $piece) {
            if (
                $piece->getId() === Symbol::ROOK &&
                $piece->getSquare() === $rule[rtrim($this->getMove()->pgn, '+')]['sq']['current']
            ) {
                return $piece;
            }
        }

        return null;
    }

    /**
     * Calculates the king's travel.
     */
    protected function setTravel(): void
    {
        $travel =  array_merge(
            (array) $this->rook->getTravel(),
            (array) $this->bishop->getTravel()
        );

        foreach($travel as $key => $val) {
            $travel[$key] = $val[0] ?? null;
        }

        $this->travel = (object) array_filter(array_unique($travel));
    }

    /**
     * Gets the king's legal moves.
     *
     * @return array
     */
    public function getSqs(): array
    {
        $sqs = array_merge(
            $this->movesKing(),
            $this->movesCaptures(),
            [$this->moveCastlingLong()],
            [$this->moveCastlingShort()]
        );

        return array_filter($sqs);
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
