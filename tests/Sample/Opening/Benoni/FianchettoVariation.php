<?php

namespace Chess\Tests\Sample\Opening\Benoni;

use Chess\Player;

class FianchettoVariation
{
    protected $movetext = '1.d4 Nf6 2.c4 c5 3.d5 e6 4.Nc3 exd5 5.cxd5 d6 6.Nf3 g6 7.g3 Bg7 8.Bg2 O-O 9.O-O Nbd7 10.Nd2 a6 11.a4 Re8';

    public function __construct()
    {
        $this->player = new Player($this->movetext);
    }

    public function play()
    {
        return $this->player->play()->getBoard();
    }
}
