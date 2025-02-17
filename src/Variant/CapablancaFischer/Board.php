<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\AbstractBoard;
use Chess\Variant\RandomBoardInterface;
use Chess\Variant\Capablanca\PGN\Move;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\CapablancaFischer\CastlingRule;
use Chess\Variant\CapablancaFischer\StartPieces;

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

    public function getStartPos(): array
    {
        return $this->shuffle;
    }
}
