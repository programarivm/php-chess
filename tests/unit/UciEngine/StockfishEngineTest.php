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

    /**
     * @test
     */
    public function get_options()
    {
        $stockfishEngine = new StockfishEngine('/usr/games/stockfish');

        $expected = [
            'Debug Log File',
            'Threads',
            'Hash',
            'Clear Hash',
            'Ponder',
            'MultiPV',
            'Skill Level',
            'Move Overhead',
            'Slow Mover',
            'nodestime',
            'UCI_Chess960',
            'UCI_AnalyseMode',
            'UCI_LimitStrength',
            'UCI_Elo',
            'UCI_ShowWDL',
            'SyzygyPath',
            'SyzygyProbeDepth',
            'Syzygy50MoveRule',
            'SyzygyProbeLimit',
            'Use NNUE',
            'EvalFile',
        ];

        $options = $stockfishEngine->getOptions();

        $this->assertSame($expected, array_keys($options));
    }
}
