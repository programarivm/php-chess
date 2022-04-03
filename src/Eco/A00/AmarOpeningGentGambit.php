<?php

namespace Chess\Eco\A00;

use Chess\Player;

class AmarOpeningGentGambit
{
    protected $movetext = '1.Nh3 d5 2.g3 e5 3.f4 Bxh3 4.Bxh3 exf4 5.O-O fxg3 6.hxg3';

    public function __construct()
    {
        $this->player = new Player($this->movetext);
    }

    public function play()
    {
        return $this->player->play()->getBoard();
    }
}
