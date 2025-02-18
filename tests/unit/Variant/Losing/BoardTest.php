<?php

namespace Chess\Tests\Unit\Variant\Losing;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Losing\Board;
use Chess\Variant\Losing\FenToBoardFactory;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $board = new Board();

        $this->assertSame(32, count($board->pieces()));
    }

    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'm', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'M', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e3_d6_Bb5_h6()
    {
        $board = new Board();

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'm', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', '.', 'p', 'p', 'p', '.' ],
            5 => [ '.', '.', '.', 'p', '.', '.', '.', 'p' ],
            4 => [ '.', 'B', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'M', '.', 'N', 'R' ],
        ];

        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'Bb5'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->doesWin());
        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e3_d6_Bb5_h6_Bxe8()
    {
        $board = new Board();

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'B', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', '.', 'p', 'p', 'p', '.' ],
            5 => [ '.', '.', '.', 'p', '.', '.', '.', 'p' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'M', '.', 'N', 'R' ],
        ];

        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'Bb5'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertFalse($board->isCheck());
        $this->assertTrue($board->play('w', 'Bxe8'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->doesWin());
        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e3_d6_Bb5_h6_Be2()
    {
        $board = new Board();

        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'Bb5'));
        $this->assertTrue($board->play('b', 'h6'));
        $this->assertFalse($board->play('w', 'Be2'));
        $this->assertFalse($board->doesWin());
    }

    /**
     * @test
     */
    public function play_e4_d5()
    {
        $board = new Board();

        $expected = ['d5'];

        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertEquals($expected, $board->legal('e4'));
    }

    /**
     * @test
     */
    public function play_e4_d5_e5()
    {
        $board = new Board();

        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertFalse($board->play('w', 'e5'));
    }

    /**
     * @test
     */
    public function play_e4_d5_exd5()
    {
        $board = new Board();

        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'exd5'));
    }

    /**
     * @test
     */
    public function w_wins()
    {
        $board = FenToBoardFactory::create('7r/8/8/8/8/1PB5/8/8 w - -');

        $this->assertFalse($board->doesWin());
        $this->assertTrue($board->play('w', 'Bxh8'));
        $this->assertTrue($board->doesWin());
    }

    /**
     * @test
     */
    public function b_wins()
    {
        $board = FenToBoardFactory::create('8/r3P3/8/8/8/8/8/8 b - -');

        $this->assertFalse($board->doesWin());
        $this->assertTrue($board->play('b', 'Rxe7'));
        $this->assertTrue($board->doesWin());
    }

    /**
     * @test
     */
    public function b_stalemates()
    {
        $board = FenToBoardFactory::create('r7/4P3/8/8/8/8/8/8 b - -');

        $this->assertFalse($board->doesWin());
        $this->assertTrue($board->play('b', 'Re8'));
        $this->assertTrue($board->doesWin());
    }

    /**
     * @test
     */
    public function play_e4_d5_exd5_Qxd5()
    {
        $board = new Board();

        $expected = ['e2'];

        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'exd5'));
        $this->assertTrue($board->play('b', 'Qxd5'));
        $this->assertEquals($expected, $board->legal('e1'));
    }
}
