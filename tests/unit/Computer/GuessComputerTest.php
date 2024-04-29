<?php

namespace Chess\Tests\Unit\Computer;

use Chess\Computer\GuessComputer;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class GuessComputerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();
        $guessComputer = new GuessComputer();

        $this->assertNotEmpty($guessComputer->move($board));
    }

    /**
     * @test
     */
    public function scholars_mate_4_w()
    {
        $movetext = '1.e4 e5 2.Qh5 Nc6 3.Bc4 Nf6';
        $board = (new SanPlay($movetext))->validate()->getBoard();
        $guessComputer = new GuessComputer();

        $this->assertNotEmpty($guessComputer->move($board));
    }

    /**
     * @test
     */
    public function scholars_mate_4_b()
    {
        $movetext = '1.e4 e5 2.Qh5 Nc6 3.Bc4 Nf6 Qxf7#';
        $board = (new SanPlay($movetext))->validate()->getBoard();
        $guessComputer = new GuessComputer();

        $this->assertSame(null, $guessComputer->move($board));
    }
}
