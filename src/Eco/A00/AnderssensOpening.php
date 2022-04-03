<?php

namespace Chess\Eco\A00;

use Chess\Player;

class AnderssensOpening
{
    protected $movetext = '1.a3';

    public function __construct()
    {
        $this->player = new Player($this->movetext);
    }

    public function play()
    {
        return $this->player->play()->getBoard();
    }
}
