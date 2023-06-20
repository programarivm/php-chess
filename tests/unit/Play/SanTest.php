<?php

namespace Chess\Tests\Unit\Play;

use Chess\Play\SAN;
use Chess\Tests\AbstractUnitTestCase;

class SanTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5()
    {
        $movetext = '1.e4 e5';
        $board = (new SAN($movetext))->play()->getBoard();

        $this->assertSame($movetext, $board->getMovetext());
    }

    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $movetext = 'foo';
        $board = (new SAN($movetext))->play()->getBoard();
    }

    /**
     * @test
     */
    public function e4_e4()
    {
        $this->expectException(\Chess\Exception\PlayException::class);

        $movetext = '1.e4 e4';
        $board = (new SAN($movetext))->play()->getBoard();
    }
}
