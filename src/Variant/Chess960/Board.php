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
    private array $shuffle;

    public function __construct(array $shuffle = null, array $pieces = null, string $castlingAbility = '-')
    {
        $this->shuffle = $shuffle ?? (new StartPosition())->create();
        $this->castlingRule = new CastlingRule($this->shuffle);
        $this->square = new Square();
        $this->move = new Move();
        if (!$pieces) {
            $pieces = (new StartPieces($this->shuffle))->create();
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

    public function getShuffle(): array
    {
        return $this->shuffle;
    }
}
