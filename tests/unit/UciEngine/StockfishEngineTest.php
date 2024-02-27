<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\Tests\AbstractUnitTestCase;
use Chess\UciEngine\StockfishEngine;
use Chess\UciEngine\Details\Limit;
use Chess\Variant\Classical\Board;

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

    /**
     * @test
     */
    public function analyse_e4()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $limit = new Limit();
        $limit->time = 3000;

        $stockfishEngine = new StockfishEngine('/usr/games/stockfish');

        $expected = 'c7c5';

        $analysis = $stockfishEngine->analyse($board, $limit);

        $this->assertSame($expected, $analysis['bestmove']);
    }
}
