<?php

namespace Chess\Tests\Unit\Computer;

use Chess\Computer\RandomMove;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class RandomMoveTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();
        $move = (new RandomMove($board))->move();

        $this->assertNotEmpty($move);
    }

    /**
     * @test
     */
    public function scholars_mate_4_w()
    {
        $movetext = '1.e4 e5 2.Qh5 Nc6 3.Bc4 Nf6';
        $board = (new SanPlay($movetext))->validate()->getBoard();
        $move = (new RandomMove($board))->move();

        $this->assertNotEmpty($move);
    }

    /**
     * @test
     */
    public function scholars_mate_4_b()
    {
        $movetext = '1.e4 e5 2.Qh5 Nc6 3.Bc4 Nf6 Qxf7#';
        $board = (new SanPlay($movetext))->validate()->getBoard();
        $move = (new RandomMove($board))->move();

        $this->assertSame(null, $move);
    }

    /**
     * @test
     */
    public function game()
    {
        $board = new Board();
        for ($i = 0; $i < 50; $i++) {
            if ($move = (new RandomMove($board))->move()) {
                $board->play($board->turn, $move->pgn);
            }
        }

        $this->assertNotEmpty($board->getMovetext());
    }
}
