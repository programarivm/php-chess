<?php

namespace Chess\Tests\Unit;

use Chess\FenToBoardFactory;
use Chess\Exception\UnknownNotationException;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board as CapablancaBoard;
use Chess\Variant\Chess960\Board as Chess960Board;
use Chess\Variant\Classical\Board as ClassicalBoard;
use Chess\Variant\RacingKings\Board as RacingKingsBoard;

class FenToBoardFactoryTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function racing_kings_Rg8_Nxf2_Qd5()
    {
        $board = FenToBoardFactory::create(
            '6R1/8/8/8/8/8/krbnNn1K/qrb1NBRQ w - -',
            new RacingKingsBoard()
        );

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

        $this->assertSame($expected, $board->toArray());
    }
}
