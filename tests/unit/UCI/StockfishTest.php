<?php

namespace Chess\Tests\Unit\Piece;

use Chess\UCI\Stockfish;
use Chess\Tests\AbstractUnitTestCase;

class StockfishTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function best_move()
    {
        $stockfish = new Stockfish();

        $stockfish->bestMove(3);

        $this->assertTrue(true);
    }
}
