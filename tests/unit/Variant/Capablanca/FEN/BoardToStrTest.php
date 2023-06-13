<?php

namespace Chess\Tests\Unit\Variant\Capablanca\FEN;

use Chess\Variant\Classical\FEN\BoardToStr;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Capablanca\Board;

class BoardToStrTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function start()
    {
        $board = new Board();

        $expected = 'rnabqkbcnr/pppppppppp/10/10/10/10/PPPPPPPPPP/RNABQKBCNR w KQkq -';
        $string = (new BoardToStr($board))->create();

        $this->assertSame($expected, $string);
    }
}
