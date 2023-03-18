<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Chess960\StartPieces;
use Chess\Variant\Chess960\Rule\CastlingRule;

/**
 * Board
 *
 * Chess board representation that allows to play a game of chess 960 in Portable
 * Game Notation (PGN) format.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
final class Board extends ClassicalBoard
{
    /**
     * Start position.
     *
     * @var array
     */
     private array $startPos = [
        Piece::R,
        Piece::N,
        Piece::B,
        Piece::Q,
        Piece::K,
        Piece::B,
        Piece::N,
        Piece::R,
     ];

    /**
     * Constructor.
     *
     * @param array $startPos
     */
    public function __construct(
        array $startPos = null,
        array $pieces = null,
        string $castlingAbility = '-'
    ) {
        if ($startPos) {
            $this->startPos = $startPos;
        }
        $this->size = Square::SIZE;
        $this->castlingRule =  (new CastlingRule($this->startPos))->getRule();
        $this->move = new Move();
        if (!$pieces) {
            $pieces = (new StartPieces($this->startPos, $this->castlingRule))->create();
            $this->castlingAbility = CastlingAbility::START;
        } else {
            $this->castlingAbility = $castlingAbility;
        }
        foreach ($pieces as $piece) {
            $this->attach($piece);
        }

        $this->refresh();
    }

    /**
     * Returns the start position.
     *
     * @return array
     */
    public function getStartPos(): array
    {
        return $this->startPos;
    }
}
