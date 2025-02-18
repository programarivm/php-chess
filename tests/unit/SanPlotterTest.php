<?php

namespace Chess\Tests\Unit;

use Chess\SanPlotter;
use Chess\Eval\FastFunction;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FenToBoardFactory;

class SanPlotterTest extends AbstractUnitTestCase
{
    static private FastFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new FastFunction();
    }

    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5()
    {
        $this->expectException(\ArgumentCountError::class);

        $board = new Board();
        $movetext = '1.e4 d5 2.exd5 Qxd5';

        $time = SanPlotter::time(self::$f, $board, $movetext);
    }

    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5_foo()
    {
        $this->expectException(\InvalidArgumentException::class);

        $board = new Board();
        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $name = 'foo';

        $time = SanPlotter::time(self::$f, $board, $movetext, $name);
    }

    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5_center()
    {
        $expected = [ 0, 1.0, 0.0833, 0.6667, -1.0 ];

        $board = new Board();
        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $name = 'Center';

        $time = SanPlotter::time(self::$f, $board, $movetext, $name);

        $this->assertSame($expected, $time);
    }

    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5_connectivity()
    {
        $expected = [ 0, -1.0, -1.0, -1.0, 1.0 ];

        $board = new Board();
        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $name = 'Connectivity';

        $time = SanPlotter::time(self::$f, $board, $movetext, $name);

        $this->assertSame($expected, $time);
    }

    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5_space()
    {
        $expected = [ 0, 1.0, 0.25, 0.50, -1.0 ];

        $board = new Board();
        $movetext = '1.e4 d5 2.exd5 Qxd5';
        $name = 'Space';

        $time = SanPlotter::time(self::$f, $board, $movetext, $name);

        $this->assertSame($expected, $time);
    }

    /**
     * @test
     */
    public function resume_E61_space()
    {
        $expected = [ 0, 1.0 ];

        $board = FenToBoardFactory::create('rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -');
        $board->playLan('b', 'f8g7');
        $board->playLan('w', 'e2e4');
        $name = 'Space';

        $time = SanPlotter::time(self::$f, new Board(), $board->movetext(), $name);

        $this->assertSame($expected, $time);
    }

    /**
     * @test
     */
    public function capablanca_e4_a5()
    {
        $expected = [ 0, 1.0, 0.8889 ];

        $board = new CapablancaBoard();
        $board->play('w', 'e4');
        $board->play('b', 'a5');
        $name = 'Center';

        $time = SanPlotter::time(self::$f, new CapablancaBoard(), $board->movetext(), $name);

        $this->assertSame($expected, $time);
    }
}
