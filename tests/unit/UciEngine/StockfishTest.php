<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\Board;
use Chess\FEN\ShortStrToPgn;
use Chess\UciEngine\Stockfish;
use Chess\Tests\AbstractUnitTestCase;

class StockfishTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function shortFen()
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
        $toFen = $stockfish->shortFen($fromFen);

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

        $fromFen = $board->toFen();
        $toFen = $stockfish->shortFen($fromFen);
        $pgn = (new ShortStrToPgn($fromFen, $toFen))->create();

        $this->assertTrue($board->play('b', current($pgn)));
        $this->assertTrue($board->play('w', 'a3'));

        $fromFen = $board->toFen();
        $toFen = $stockfish->shortFen($fromFen);
        $pgn = (new ShortStrToPgn($fromFen, $toFen))->create();

        $this->assertTrue($board->play('b', current($pgn)));
    }
}
