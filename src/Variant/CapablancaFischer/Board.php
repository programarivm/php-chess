<?php

namespace Chess\Variant\CapablancaFischer;

use Chess\Variant\AbstractBoard;
use Chess\Variant\Capablanca\PGN\Move;
use Chess\Variant\Capablanca\PGN\Square;
use Chess\Variant\CapablancaFischer\CastlingRule;
use Chess\Variant\CapablancaFischer\RandomPieceArrayFactory;

class Board extends AbstractBoard
{
    public function __construct(array $shuffle = null, array $pieces = null, string $castlingAbility = '-')
    {
        $this->castlingRule = new CastlingRule($shuffle);
        $this->square = new Square();
        $this->move = new Move();
        if (!$pieces) {
            $pieces = RandomPieceArrayFactory::create($shuffle);
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
}
