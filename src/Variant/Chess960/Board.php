<?php

namespace Chess\Variant\Chess960;

use Chess\Board;
use Chess\Variant\Chess960\StartingPieces;

final class Board extends Board
{
    public function __construct()
    {
        $pieces = (new StartingPieces())->create();

        // TODO
    }
}
