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
    public function best_move_e2e4()
    {
        $stockfish = new Stockfish(new Board());

        $bestMove = $stockfish->bestMove('e2e4', 3);

        $board = $stockfish->play($bestMove)->getBoard();

        $this->assertNotEmpty($bestMove);
    }
}
