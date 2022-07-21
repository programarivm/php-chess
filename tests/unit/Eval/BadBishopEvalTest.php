<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\BadBishopEval;
use Chess\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class BadBishopEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function position_01()
    {
        $fen = '8/5b2/p2k4/1p1p1p1p/1P1K1P1P/2P1PB2/8/8 w - - 0 1';

        $board = (new StrToBoard($fen))->create();

        $expected = [
            'w' => 0,
            'b' => 5,
        ];

        $badBishopEval = (new BadBishopEval($board))->eval();

        $this->assertSame($expected, $badBishopEval);
    }
}
