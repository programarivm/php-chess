<?php

namespace Chess\Tests\Unit\Computer;

use Chess\Computer\RandomComputer;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class RandomComputerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();
        $randomComputer = new RandomComputer();

        $this->assertNotEmpty($randomComputer->move($board));
    }
}
