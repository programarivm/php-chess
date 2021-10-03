<?php

namespace Chess\Tests\Sample\Opening\QueensGambit;

use Chess\Player;

class SymmetricalDefense
{
    protected $movetext = '1.d4 d5 2.c4 c5';

    public function __construct()
    {
        $this->player = new Player($this->movetext);
    }

    public function play()
    {
        return $this->player->play()->getBoard();
    }
}
