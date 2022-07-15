<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\UciEngine\Stockfish;
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
