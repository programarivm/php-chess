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

        $this->rook = new Rook($color, $sq, RookType::FAKED);
        $this->bishop = new Bishop($color, $sq);

        $this->scope();
    }

    protected function moveCastlingLong()
    {
        $rule = Castling::color($this->getColor())[Symbol::KING][Symbol::CASTLING_LONG];
        if (!$this->board->getCastling()[$this->getColor()]['isCastled']) {
            if ($this->board->getCastling()[$this->getColor()][Symbol::CASTLING_LONG]) {
                if (
                    in_array($rule['sqs']['b'], $this->board->getSquares()->free) &&
                    in_array($rule['sqs']['c'], $this->board->getSquares()->free) &&
                    in_array($rule['sqs']['d'], $this->board->getSquares()->free) &&
                    !in_array($rule['sqs']['b'], $this->board->getSpace()->{$this->getOppColor()}) &&
                    !in_array($rule['sqs']['c'], $this->board->getSpace()->{$this->getOppColor()}) &&
                    !in_array($rule['sqs']['d'], $this->board->getSpace()->{$this->getOppColor()})
                ) {
                    return $rule['sq']['next'];
                }
            }
        }

        return null;
    }

    protected function moveCastlingShort()
    {
        $rule = Castling::color($this->getColor())[Symbol::KING][Symbol::CASTLING_SHORT];
        if (!$this->board->getCastling()[$this->getColor()]['isCastled']) {
            if ($this->board->getCastling()[$this->getColor()][Symbol::CASTLING_SHORT]) {
                if (
                    in_array($rule['sqs']['f'], $this->board->getSquares()->free) &&
                    in_array($rule['sqs']['g'], $this->board->getSquares()->free) &&
                    !in_array($rule['sqs']['f'], $this->board->getSpace()->{$this->getOppColor()}) &&
                    !in_array($rule['sqs']['g'], $this->board->getSpace()->{$this->getOppColor()})
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
            array_values((array)$this->scope),
            $this->board->getSquares()->used->{$this->getOppColor()}
        );

        return array_diff($movesCaptures, $this->board->getDefense()->{$this->getOppColor()});
    }

    protected function movesKing()
    {
        $movesKing = array_intersect(array_values((array)$this->scope), $this->board->getSquares()->free);

        return array_diff($movesKing, $this->board->getSpace()->{$this->getOppColor()});
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
     * Calculates the king's scope.
     */
    protected function scope(): void
    {
        $scope =  array_merge(
            (array) $this->rook->getScope(),
            (array) $this->bishop->getScope()
        );

        foreach($scope as $key => $val) {
            $scope[$key] = $val[0] ?? null;
        }

        $this->scope = (object) array_filter(array_unique($scope));
    }

    /**
     * Gets the king's legal moves.
     *
     * @return array
     */
    public function getSquares(): array
    {
        $sqs = array_merge(
            $this->movesKing(),
            $this->movesCaptures(),
            [$this->moveCastlingLong()],
            [$this->moveCastlingShort()]
        );

        return array_filter($sqs);
    }

    public function getDefendedSquares(): array
    {
        $sqs = [];
        foreach ($this->scope as $sq) {
            if (in_array($sq, $this->board->getSquares()->used->{$this->getColor()})) {
                $sqs[] = $sq;
            }
        }

        return $sqs;
    }
}
