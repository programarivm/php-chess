<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\RandomBoardInterface;
use Chess\Variant\Capablanca\PGN\Move;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\CapablancaFischer\StartPieces;
use Chess\Variant\CapablancaFischer\Rule\CastlingRule;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Board
 *
 * Capablanca-Fischer chessboard representation.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
final class Board extends ClassicalBoard implements RandomBoardInterface
{
    const VARIANT = 'capablanca-fischer';

    /**
     * Start position.
     *
     * @var array
     */
     private array $startPos;

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
        $this->color = new Color();
        $this->startPos = $startPos ?? (new StartPosition())->getDefault();
        $this->castlingRule = new CastlingRule($this->startPos);
        $this->square = new Square();
        $this->move = new Move();
        if (!$pieces) {
            $pieces = (new StartPieces($this->startPos, $this->castlingRule))->create();
            $this->castlingAbility = CastlingRule::START;
        } else {
            $this->castlingAbility = $castlingAbility;
        }
        foreach ($pieces as $piece) {
            $this->attach($piece);
        }

        $this->refresh();

        $this->startFen = $this->toFen();
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
