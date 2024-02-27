<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\Tests\AbstractUnitTestCase;
use Chess\UciEngine\StockfishEngine;

class StockfishEngineTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function instantiation()
    {
        $stockfishEngine = new StockfishEngine('/usr/games/stockfish');

        $this->assertTrue(is_a($stockfishEngine, StockfishEngine::class));
    }
}
