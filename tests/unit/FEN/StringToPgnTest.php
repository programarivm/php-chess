<?php

namespace Chess\Tests\Unit\Fen;

use Chess\FEN\StringToPgn;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;

class StringToPgnTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function e4()
    {
        $pgn = (new StringToPgn(
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3'
        ))->create();

        $expected = [
            Symbol::WHITE => 'e4',
        ];

        $this->assertEquals($expected, $pgn);
    }

    /**
     * @test
     */
    public function Nf3()
    {
        $pgn = (new StringToPgn(
            'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -',
            'rnbqkbnr/pppppppp/8/8/8/5N2/PPPPPPPP/RNBQKB1R b KQkq -'
        ))->create();

        $expected = [
            Symbol::WHITE => 'Nf3',
        ];

        $this->assertEquals($expected, $pgn);
    }
}
