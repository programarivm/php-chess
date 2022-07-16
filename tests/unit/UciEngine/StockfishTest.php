<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\Board;
use Chess\UciEngine\Stockfish;
use Chess\Tests\AbstractUnitTestCase;

class StockfishTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function best_move_e4()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $stockfish = new Stockfish($board);

        $bestMove = $stockfish->bestMove($board->toFen(), 3);

        $this->assertNotEmpty($bestMove);
    }
}
