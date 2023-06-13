<?php

namespace Chess\Tests\Unit;

use Chess\Grandmaster;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board as ClassicalBoard;

class GrandmasterTest extends AbstractUnitTestCase
{
    const FILEPATH = __DIR__.'/../data/json/players.json';

    /**
     * @test
     */
    public function w_move()
    {
        $board = new ClassicalBoard();

        $move = (new Grandmaster(self::FILEPATH))->move($board);

        $this->assertNotEmpty($move);
    }

    /**
     * @test
     */
    public function b_move()
    {
        $board = new ClassicalBoard();

        $board->play('w', 'e4');

        $move = (new Grandmaster(self::FILEPATH))->move($board);

        $this->assertNotEmpty($move);
    }
}
