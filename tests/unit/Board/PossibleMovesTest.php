<?php

namespace Chess\Tests\Unit\Board;

use Chess\Board;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;

class PossibleMovesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();
        $possibleMoves = $board->getPossibleMoves();
        $expected = [
            'Na3',
            'Nc3',
            'Nf3',
            'Nh3',
            'a3',
            'a4',
            'b3',
            'b4',
            'c3',
            'c4',
            'd3',
            'd4',
            'e3',
            'e4',
            'f3',
            'f4',
            'g3',
            'g4',
            'h3',
            'h4',
        ];

        $this->assertEquals($expected, $possibleMoves);
    }
}
