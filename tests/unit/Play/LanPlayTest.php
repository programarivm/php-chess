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

        $board = (new LanPlay('foo'))->validate()->board;
    }

    /**
     * @test
     */
    public function e2e4_e2e4()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $board = (new LanPlay('e2e4 e2e4'))->validate()->board;
    }

    /**
     * @test
     */
    public function e2e4_e7e5()
    {
        $expected = '1.e4 e5';
        
        $board = (new LanPlay('e2e4  e7e5'))->validate()->board;

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function b1a3_d7d5()
    {
        $expected = '1.Na3 d5';
        
        $board = (new LanPlay('b1a3 d7d5'))->validate()->board;

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function e2e4_e7e5___g1f3()
    {
        $expected = '1.e4 e5 2.Nf3';

        $board = (new LanPlay('e2e4 e7e5   g1f3'))->validate()->board;

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function e2e4_d7d5_a2a3_d5e4()
    {
        $expected = '1.e4 d5 2.a3 dxe4';

        $board = (new LanPlay('e2e4 d7d5 a2a3 d5e4'))->validate()->board;

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function cxb8_q()
    {
        $expected = '1.a4 h5 2.a5 b5 3.axb6 h4 4.bxc7 h3 5.cxb8=Q';

        $board = (new LanPlay('a2a4 h7h5 a4a5 b7b5 a5b6 h5h4 b6c7 h4h3 c7b8'))->validate()->board;

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function b4_a6_b5_h6_h3_c5_g3_h5_b6()
    {
        $expected = 'rnbqkbnr/1p1pppp1/pP6/2p4p/8/6PP/P1PPPP2/RNBQKBNR b KQkq - 0 5';

        $board = (new LanPlay('b2b4 a7a6 b4b5 h7h6 h2h3 c7c5 g2g3 h6h5 b5b6'))->validate()->board;

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function a4_b5_axb5_a6_b6()
    {
        $expected = 'rnbqkbnr/2pppppp/pP6/8/8/8/1PPPPPPP/RNBQKBNR b KQkq - 0 3';

        $board = (new LanPlay('a2a4 b7b5 a4b5 a7a6 b5b6'))->validate()->board;

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function c00()
    {
        $expected = '1.e4 e6 2.d4 d5 3.Be3';

        $board = (new LanPlay('e2e4 e7e6 d2d4 d7d5 c1e3'))->validate()->board;

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function b00()
    {
        $expected = '1.e4 Nc6 2.Nf3 d6 3.Be2 Be6 4.O-O Qd7 5.h3 O-O-O';

        $board = (new LanPlay('e2e4 b8c6 g1f3 d7d6 f1e2 c8e6 e1g1 d8d7 h2h3 e8c8'))->validate()->board;

        $this->assertSame($expected, $board->movetext());
    }
}
