<?php

namespace Chess;

use Chess\Board;

class Composition
{
    protected $board;

    public function __construct(Board $board)
    {
        $this->board = unserialize(serialize($board));
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function deletePieceByPosition(string $square)
    {
        $piece = $this->board->getPieceByPosition($square);
        $this->board->detach($piece);
        $this->board->refresh();

        return $this;
    }

    public function setTurn(string $color)
    {
        $this->board->setTurn($color);

        return $this;
    }
}
