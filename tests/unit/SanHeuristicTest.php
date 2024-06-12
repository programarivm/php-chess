<?php

namespace Chess\Tests\Unit;

use Chess\SanHeuristic;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\FEN\StrToBoard;

class SanHeuristicTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4_d5_exd5_Qxd5()
    {
        $name = 'Space';

        $movetext = '1.e4 d5 2.exd5 Qxd5';

        $balance = (new SanHeuristic($name, $movetext))->getBalance();

        $expected = [ 0, 1.0, 0.25, 0.50, -1.0 ];

        $this->assertSame($expected, $balance);
    }

    /**
     * @test
     */
    public function resume_E61()
    {
        $name = 'Space';

        $board = (new StrToBoard('rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'))
            ->create();

        $board->playLan('b', 'f8g7');
        $board->playLan('w', 'e2e4');

        $balance = (new SanHeuristic($name, $board->getMovetext()))->getBalance();

        $expected = [ 0, 1.0 ];

        $this->assertSame($expected, $balance);
    }
}
