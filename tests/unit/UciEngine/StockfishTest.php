<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\Variant\Classical\FEN\ShortStrToPgn;
use Chess\UciEngine\Stockfish;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class StockfishTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function bestMove()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $stockfish = (new Stockfish($board))
            ->setOptions([
                'Skill Level' => 9
            ])
            ->setParams([
                'depth' => 3
            ]);

        $fromFen = $board->toFen();
        $toFen = $stockfish->bestMove($fromFen);

        $this->assertNotEmpty($toFen);
    }

    /**
     * @test
     */
    public function play()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $stockfish = (new Stockfish($board))
            ->setOptions([
                'Skill Level' => 17
            ])
            ->setParams([
                'depth' => 8
            ]);

        $uci = $stockfish->play($board->toFen());

        $this->assertTrue($board->playUci('b', $uci));
        $this->assertTrue($board->playUci('w', 'a2a3'));

        $uci = $stockfish->play($board->toFen());

        $this->assertTrue($board->playUci('b', $uci));
    }
}
