<?php

namespace Chess\Tests\Unit;

use Chess\Board;
use Chess\Tests\AbstractUnitTestCase;

class ArrayOfBoardsTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_e5_d4_exd4_on_two_boards()
    {
        $boards = [];
        $boards[] = new Board();
        $boards[] = new Board();

        $this->assertTrue($boards[0]->play('w', 'e4'));
        $this->assertTrue($boards[1]->play('w', 'e4'));

        $this->assertTrue($boards[0]->play('b', 'e5'));
        $this->assertTrue($boards[1]->play('b', 'e5'));

        $this->assertTrue($boards[0]->play('w', 'd4'));
        $this->assertTrue($boards[1]->play('w', 'd4'));

        $this->assertTrue($boards[0]->play('b', 'exd4'));
        $this->assertTrue($boards[1]->play('b', 'exd4'));
    }

    /**
     * @test
     */
    public function e4_c5_Nf3_d6_on_four_boards()
    {
        $boards = [];
        $boards[] = new Board();
        $boards[] = new Board();
        $boards[] = new Board();
        $boards[] = new Board();

        $this->assertTrue($boards[0]->play('w', 'e4'));
        $this->assertTrue($boards[1]->play('w', 'e4'));
        $this->assertTrue($boards[2]->play('w', 'e4'));
        $this->assertTrue($boards[3]->play('w', 'e4'));

        $this->assertTrue($boards[0]->play('b', 'c5'));
        $this->assertTrue($boards[1]->play('b', 'c5'));
        $this->assertTrue($boards[2]->play('b', 'c5'));
        $this->assertTrue($boards[3]->play('b', 'c5'));

        $this->assertTrue($boards[0]->play('w', 'Nf3'));
        $this->assertTrue($boards[1]->play('w', 'Nf3'));
        $this->assertTrue($boards[2]->play('w', 'Nf3'));
        $this->assertTrue($boards[3]->play('w', 'Nf3'));

        $this->assertTrue($boards[0]->play('b', 'd6'));
        $this->assertTrue($boards[1]->play('b', 'd6'));
        $this->assertTrue($boards[2]->play('b', 'd6'));
        $this->assertTrue($boards[3]->play('b', 'd6'));
    }
}
