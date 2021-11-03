<?php

namespace Chess\Tests\Sample\Opening\FrenchDefense;

use Chess\Player;

class Exchange
{
    protected $movetext = '1.e4 e6 2.d4 d5 3.exd5 exd5';

    public function __construct()
    {
        $this->player = new Player($this->movetext);
    }

    public function play()
    {
        return $this->player->play()->getBoard();
    }
}
