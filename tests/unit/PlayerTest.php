<?php

namespace Chess\Tests\Unit;

use Chess\Player;
use Chess\Tests\AbstractUnitTestCase;

class PlayerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5()
    {
        $movetext = '1.e4 e5';
        $board = (new Player($movetext))->play()->getBoard();

        $this->assertSame($movetext, $board->getMovetext());
    }
}
