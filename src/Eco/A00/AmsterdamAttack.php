<?php

namespace Chess\Eco\A00;

use Chess\Player;

class AmsterdamAttack
{
    protected $movetext = '1.e3 e5 2.c4 d6 3.Nc3 Nc6 4.b3 Nf6';

    public function __construct()
    {
        $this->player = new Player($this->movetext);
    }

    public function play()
    {
        return $this->player->play()->getBoard();
    }
}
