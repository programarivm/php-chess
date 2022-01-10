<?php

namespace Chess\Tests\Unit;

use Chess\Player;
use Chess\Exception\MovetextException;
use Chess\Exception\UnknownNotationException;
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

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(UnknownNotationException::class);

        $movetext = 'foo';
        $board = (new Player($movetext))->play()->getBoard();
    }

    /**
     * @test
     */
    public function e4_e4()
    {
        $this->expectException(MovetextException::class);

        $movetext = '1.e4 e4';
        $board = (new Player($movetext))->play()->getBoard();
    }
}
