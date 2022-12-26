<?php

namespace Chess\Tests\Unit\Player;

use Chess\Player\LanPlayer;
use Chess\Tests\AbstractUnitTestCase;

class LanPlayerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\PlayerException::class);

        $lanMovetext = 'foo';
        $board = (new LanPlayer($lanMovetext))->play()->getBoard();
    }

    /**
     * @test
     */
    public function e2e4_e2e4()
    {
        $this->expectException(\Chess\Exception\PlayerException::class);

        $lanMovetext = 'e2e4 e2e4';
        $board = (new LanPlayer($lanMovetext))->play()->getBoard();
    }
    
    /**
     * @test
     */
    public function e2e4_e7e5()
    {
        $lanMovetext = 'e2e4 e7e5';

        $board = (new LanPlayer($lanMovetext))->play()->getBoard();

        $expected = '1.e4 e5';

        $this->assertSame($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function e2e4__e7e5()
    {
        $lanMovetext = 'e2e4  e7e5';

        $board = (new LanPlayer($lanMovetext))->play()->getBoard();

        $expected = '1.e4 e5';

        $this->assertSame($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function e2e4_e7e5_g1f3()
    {
        $lanMovetext = 'e2e4 e7e5 g1f3';

        $board = (new LanPlayer($lanMovetext))->play()->getBoard();

        $expected = '1.e4 e5 2.Nf3';

        $this->assertSame($expected, $board->getMovetext());
    }

    /**
     * @test
     */
    public function e2e4_e7e5___g1f3()
    {
        $lanMovetext = 'e2e4 e7e5   g1f3';

        $board = (new LanPlayer($lanMovetext))->play()->getBoard();

        $expected = '1.e4 e5 2.Nf3';

        $this->assertSame($expected, $board->getMovetext());
    }
}
