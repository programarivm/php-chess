<?php

namespace Chess\Eval;

use Chess\Piece\AbstractPiece;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

/**
 * Attack evaluation.
 *
 * Piece value obtained from the squares under threat of being attacked. A
 * sequence of moves will be played in a cloned chess board to determine if a
 * capture can result in a gain of material.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class AttackEval extends AbstractEval implements
    ElaborateEvalInterface,
    ExplainEvalInterface
{
    use ElaborateEvalTrait;
    use ExplainEvalTrait;

    const NAME = 'Attack';

    /**
     * Constructor.
     *
     * @param \Chess\Variant\Classical\Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->range = [0.8, 5];

        $this->subject = [
            'White',
            'Black',
        ];

        $this->observation = [
            "has a slight attack advantage",
            "has a moderate attack advantage",
            "has a total attack advantage",
        ];

        if (
            !$this->board->isCheck() &&
            !$this->board->isMate() &&
            !$this->board->isStalemate()
        ) {
            foreach ($this->board->getPieces() as $piece) {
                if ($piece->getId() !== Piece::K) {
                    $clone = unserialize(serialize($this->board));
                    $clone->setTurn($piece->oppColor());
                    $attack = [
                        Color::W => 0,
                        Color::B => 0,
                    ];
                    $attackingPieces = $piece->attackingPieces();
                    $defendingPieces = $piece->defendingPieces();
                    foreach ($attackingPieces as $attackingPiece) {
                        $capturedPiece = $clone->getPieceBySq($piece->getSq());
                        if ($clone->playLan($clone->getTurn(), $attackingPiece->getSq() . $piece->getSq())) {
                            $attack[$attackingPiece->getColor()] += self::$value[$capturedPiece->getId()];
                            foreach ($defendingPieces as $defendingPiece) {
                                $capturedPiece = $clone->getPieceBySq($piece->getSq());
                                if ($clone->playLan($clone->getTurn(), $defendingPiece->getSq() . $piece->getSq())) {
                                    $attack[$defendingPiece->getColor()] += self::$value[$capturedPiece->getId()];
                                }
                            }
                        }
                    }
                    $diff = $attack[Color::W] - $attack[Color::B];
                    if ($piece->oppColor() === Color::W) {
                        if ($diff > 0) {
                            $this->result[Color::W] += $diff;
                            $this->elaborate($piece);
                        }
                    } else {
                        if ($diff < 0) {
                            $this->result[Color::B] += abs($diff);
                            $this->elaborate($piece);
                        }
                    }
                }
            }
        }

        $this->explain($this->result);
    }

    /**
     * Elaborate on the result.
     *
     * @param \Chess\Piece\AbstractPiece $piece
     */
    private function elaborate(AbstractPiece $piece): void
    {
        $this->elaboration[] = "The {$piece->getSq()}-square is under threat of being attacked.";
    }
}
