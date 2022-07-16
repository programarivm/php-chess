<?php

namespace Chess\Tests\Unit\UciEngine;

use Chess\Board;
use Chess\FEN\StrToBoard;
use Chess\UciEngine\Stockfish;
use Chess\Tests\AbstractUnitTestCase;

class StockfishTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function fen()
    {
        $board = new Board();
        $board->play('w', 'e4');

        $stockfish = new Stockfish($board);
        $fen = $stockfish->fen($board->toFen(), 3);

        $board = (new StrToBoard($fen))->create();
        $board->play('w', 'Ke2');

        $stockfish = new Stockfish($board);
        $fen = $stockfish->fen($board->toFen(), 3);

        $this->assertNotEmpty($fen);
    }
}
