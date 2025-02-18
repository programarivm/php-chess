<?php

namespace Chess\Tests\Unit\Variant\Dunsany;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Dunsany\Board;
use Chess\Variant\Dunsany\FenToBoardFactory;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $board = new Board();

        $this->assertSame(48, count($board->pieces()));
    }

    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            2 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
        ];

        $this->assertSame($expected, $board->toArray());
        $this->assertSame('b', $board->turn);
    }

    /**
     * @test
     */
    public function play_e5()
    {
        $board = new Board();
        $board->play('b', 'e5');

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.' ],
            3 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            2 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_Nc6_d5()
    {
        $board = new Board();

        $expected = '1...Nc6 2.d5 e5';

        $this->assertTrue($board->play('b', 'Nc6'));
        $this->assertTrue($board->play('w', 'd5'));
        $this->assertTrue($board->play('b', 'e5'));
        $this->assertFalse($board->play('w', 'a6'));
        $this->assertEquals($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function play_lan_e7e6()
    {
        $board = new Board();
        $board->playLan('b', 'e7e6');

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', 'p', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            2 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function b_wins()
    {
        $board = FenToBoardFactory::create(
            '5k2/2r5/8/8/2P5/8/8/8 b - -',
            new Board()
        );

        $expected = [
            7 => [ '.', '.', '.', '.', '.', 'k', '.', '.' ],
            6 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', 'r', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            0 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
        ];

        $this->assertSame('b', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertTrue($board->play('b', 'Rxc4'));
        $this->assertSame('w', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->doesWin());
        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function w_wins()
    {
        $board = FenToBoardFactory::create(
            '7k/3R4/8/8/8/8/8/R7 w - -',
            new Board()
        );

        $expected = [
            7 => [ 'R', '.', '.', '.', '.', '.', '.', 'k' ],
            6 => [ '.', '.', '.', 'R', '.', '.', '.', '.' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            0 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
        ];

        $this->assertSame('w', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertTrue($board->play('w', 'Ra8'));
        $this->assertSame('b', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertTrue($board->isMate());
        $this->assertTrue($board->doesWin());
        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function Rh8_stalemates()
    {
        $board = FenToBoardFactory::create(
            'r7/3k3P/8/8/8/8/8/8 b - -',
            new Board()
        );

        $expected = [
            7 => [ '.', '.', '.', '.', '.', '.', '.', 'r' ],
            6 => [ '.', '.', '.', 'k', '.', '.', '.', 'P' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            0 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
        ];

        $this->assertSame('b', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertTrue($board->play('b', 'Rh8'));
        $this->assertSame('w', $board->turn);
        $this->assertTrue($board->isStalemate());
        $this->assertFalse($board->isMate());
        $this->assertFalse($board->doesWin());
        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function Kxh7_stalemates()
    {
        $board = FenToBoardFactory::create(
            '7k/7P/8/7P/8/8/8/8 w - -',
            new Board()
        );

        $expected = [
            7 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            6 => [ '.', '.', '.', '.', '.', '.', '.', 'k' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', 'P' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            0 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
        ];

        $this->assertSame('w', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertTrue($board->play('w', 'h6'));
        $this->assertSame('b', $board->turn);
        $this->assertFalse($board->isStalemate());
        $this->assertFalse($board->isMate());
        $this->assertFalse($board->doesWin());
        $this->assertTrue($board->play('b', 'Kxh7'));
        $this->assertTrue($board->isStalemate());
        $this->assertFalse($board->isMate());
        $this->assertFalse($board->doesWin());
        $this->assertSame($expected, $board->toArray());
    }
}
