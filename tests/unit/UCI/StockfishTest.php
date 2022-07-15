<?php

namespace Chess\Tests\Unit\Piece;

use Chess\UCI\Stockfish;
use Chess\Tests\AbstractUnitTestCase;

class StockfishTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function best_move_e2e4()
    {
        $bestMove = (new Stockfish())->bestMove('e2e4', 3);

        $this->assertNotEmpty($bestMove);
    }
}
