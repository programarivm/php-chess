<?php

namespace Chess\Tests\Unit\Play;

use Chess\Play\LanPlay;
use Chess\Tests\AbstractUnitTestCase;

class LanPlayTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $movetext = 'foo';
        $board = (new LanPlay($movetext))->validate()->board;
    }

    /**
     * @test
     */
    public function e2e4_e2e4()
    {
        $this->expectException(\Chess\Exception\PlayException::class);

        $movetext = 'e2e4 e2e4';
        $board = (new LanPlay($movetext))->validate()->board;
    }

    /**
     * @test
     */
    public function e2e4_e7e5()
    {
        $movetext = 'e2e4 e7e5';

        $board = (new LanPlay($movetext))->validate()->board;

        $expected = '1.e4 e5';

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function e2e4__e7e5()
    {
        $movetext = 'e2e4  e7e5';

        $board = (new LanPlay($movetext))->validate()->board;

        $expected = '1.e4 e5';

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function e2e4_e7e5_g1f3()
    {
        $movetext = 'e2e4 e7e5 g1f3';

        $board = (new LanPlay($movetext))->validate()->board;

        $expected = '1.e4 e5 2.Nf3';

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function e2e4_e7e5___g1f3()
    {
        $movetext = 'e2e4 e7e5   g1f3';

        $board = (new LanPlay($movetext))->validate()->board;

        $expected = '1.e4 e5 2.Nf3';

        $this->assertSame($expected, $board->movetext());
    }
}
