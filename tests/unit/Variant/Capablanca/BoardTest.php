<?php

namespace Chess\Tests\Unit\Variant\Capablanca;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $board = new Board();

        $this->assertSame(40, count($board->pieces()));
    }

    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = [
            7 => [ 'r', 'n', 'a', 'b', 'q', 'k', 'b', 'c', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', 'B', 'C', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e4_e5()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $expected = [
            7 => [ 'r', 'n', 'a', 'b', 'q', 'k', 'b', 'c', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', 'B', 'C', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_e4_e5_Nh3()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nh3');

        $expected = [
            7 => [ 'r', 'n', 'a', 'b', 'q', 'k', 'b', 'c', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', 'N', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', 'B', 'C', '.', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }


    /**
     * @test
     */
    public function play_Nj3_e5___Ci6_O_O()
    {
        $board = new Board();

        $board->play('w', 'Nj3');
        $board->play('b', 'e5');
        $board->play('w', 'Ci3');
        $board->play('b', 'Nc6');
        $board->play('w', 'h3');
        $board->play('b', 'b6');
        $board->play('w', 'Bh2');
        $board->play('b', 'Ci6');
        $board->play('w', 'O-O');

        $expected = [
            7 => [ 'r', '.', 'a', 'b', 'q', 'k', 'b', '.', 'n', 'r' ],
            6 => [ 'p', '.', 'p', 'p', '.', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', 'p', 'n', '.', '.', '.', '.', '.', 'c', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', 'P', 'C', 'N' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'B', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', '.', '.', 'R', 'K', '.' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_Nj3_e5___Ci6_O_O_undo()
    {
        $board = new Board();

        $board->play('w', 'Nj3');
        $board->play('b', 'e5');
        $board->play('w', 'Ci3');
        $board->play('b', 'Nc6');
        $board->play('w', 'h3');
        $board->play('b', 'b6');
        $board->play('w', 'Bh2');
        $board->play('b', 'Ci6');
        $board->play('w', 'O-O');
        $board->undo();

        $expected = [
            7 => [ 'r', '.', 'a', 'b', 'q', 'k', 'b', '.', 'n', 'r' ],
            6 => [ 'p', '.', 'p', 'p', '.', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', 'p', 'n', '.', '.', '.', '.', '.', 'c', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', 'P', 'C', 'N' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'B', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', '.', '.', '.', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_lan_e2e4_e7e5_i1h3()
    {
        $board = new Board();

        $board->playLan('w', 'e2e4');
        $board->playLan('b', 'e7e5');
        $board->playLan('w', 'i1h3');

        $expected = [
            7 => [ 'r', 'n', 'a', 'b', 'q', 'k', 'b', 'c', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', 'N', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', 'B', 'C', '.', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }
}
