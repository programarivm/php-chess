<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RandomBoardInterface;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\PGN\Square;
use Chess\Variant\Chess960\CastlingRule;
use Chess\Variant\Chess960\StartPieces;

class Board extends AbstractBoard implements RandomBoardInterface
{
    private array $startPos;

    public function __construct(array $startPos = null, array $pieces = null, string $castlingAbility = '-')
    {
        $this->startPos = $startPos ?? (new StartPosition())->create();
        $this->castlingRule = new CastlingRule($this->startPos);
        $this->square = new Square();
        $this->move = new Move();
        if (!$pieces) {
            $pieces = (new StartPieces($this->startPos))->create();
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

    public function getStartPos(): array
    {
        return $this->startPos;
    }
}
