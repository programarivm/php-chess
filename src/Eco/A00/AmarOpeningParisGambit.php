<?php

namespace Chess\Eco\A00;

use Chess\Player;

class AmarOpeningParisGambit
{
    protected $movetext = '1.Nh3 d5 2.g3 e5 3.f4';

    public function __construct()
    {
        $this->player = new Player($this->movetext);
    }

    public function play()
    {
        return $this->player->play()->getBoard();
    }
}
