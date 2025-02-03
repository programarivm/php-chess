<?php

namespace Chess\Tests\Unit\Variant\CapablancaFischer;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\CapablancaFischer\FenToBoardFactory;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function ARNBQKBNRC_e4()
    {
        $startPos = ['A', 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R', 'C' ];

        $board = FenToBoardFactory::create(
            'arnbqkbnrc/pppppppppp/10/10/5P4/10/PPPPP1PPPP/ARNBQKBNRC b KQkq e3 0 1',
            $startPos
        );

        $array = $board->toArray();

        $expected = [
            7 => [ 'a', 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r', 'c' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', 'P', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P', 'P' ],
            0 => [ 'A', 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R', 'C' ],
        ];

        $this->assertSame($expected, $array);
    }
}
