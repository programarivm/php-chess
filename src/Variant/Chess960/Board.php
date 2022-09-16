<?php

namespace Chess\Variant\Chess960;

use Chess\Board as ClassicalBoard;
use Chess\FEN\Field\CastlingAbility;
use Chess\Variant\Chess960\StartPieces;

final class Board extends ClassicalBoard
{
    public function __construct(array $pieces = null, string $castlingAbility = '-')
    {
        if (!$pieces) {
            $pieces = (new StartPieces())->create();
            $this->castlingAbility = CastlingAbility::START;
        } else {
            $this->castlingAbility = $castlingAbility;
        }

        foreach ($pieces as $piece) {
            $this->attach($piece);
        }
    }

    // TODO ...
}
