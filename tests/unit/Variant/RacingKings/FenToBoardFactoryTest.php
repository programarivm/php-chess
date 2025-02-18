<?php

namespace Chess\Tests\Unit\Variant\RacingKings;

use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\RacingKings\FenToBoardFactory;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function null_string()
    {
        $expected = [
            7 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            6 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'k', 'r', 'b', 'n', 'N', 'B', 'R', 'K' ],
            0 => [ 'q', 'r', 'b', 'n', 'N', 'B', 'R', 'Q' ],
        ];

        $board = FenToBoardFactory::create();

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function Rg8_Nxf2_Qd5()
    {
        $expected = [
            7 => [ '.', '.', '.', '.', '.', '.', 'R', '.' ],
            6 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'k', 'r', 'b', 'n', 'N', 'n', '.', 'K' ],
            0 => [ 'q', 'r', 'b', '.', 'N', 'B', 'R', 'Q' ],
        ];

        $board = FenToBoardFactory::create('6R1/8/8/8/8/8/krbnNn1K/qrb1NBRQ w - -');

        $this->assertSame($expected, $board->toArray());
    }
}
