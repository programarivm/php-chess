<?php

namespace Chess\Tests\Unit\Variant\Capablanca;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\FenToBoardFactory;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function null_string()
    {
        $expected = [
            7 => [ 'r', 'n', 'a', 'b', 'q', 'k', 'b', 'c', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', 'B', 'C', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create();

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function f4()
    {
        $expected = [
            7 => [ 'r', 'n', 'a', 'b', 'q', 'k', 'b', 'c', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', 'P', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', 'B', 'C', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('rnabqkbcnr/pppppppppp/10/10/5P4/10/PPPPP1PPPP/RNABQKBCNR b KQkq f3');

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function e4_e5()
    {
        $expected = [
            7 => [ 'r', 'n', 'a', 'b', 'q', 'k', 'b', 'c', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'A', 'B', 'Q', 'K', 'B', 'C', 'N', 'R' ],
        ];

        $board = FenToBoardFactory::create('rnabqkbcnr/pppp1ppppp/10/4p5/4P5/10/PPPP1PPPPP/RNABQKBCNR w KQkq e6');

        $this->assertSame($expected, $board->toArray());
    }
}
