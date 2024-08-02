<?php

namespace Chess\Tests\unit\Eval;

use Chess\Eval\OverloadingEval;
use Chess\FenToBoardFactory;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\Board;

class OverloadingEvalTest extends AbstractUnitTestCase
{
    public function test_start()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $result = (new OverloadingEval(new Board()))->getResult();

        $this->assertSame($expected, $result);
    }

    public function test_both_sides_have_one_overloaded_piece()
    {
        $expected = [
            'w' => 1,
            'b' => 1,
        ];

        $board = FenToBoardFactory::create('6k1/1p5p/p2p4/P2nrb2/1P1N4/2P2B2/5K2/4R3 w KQkq - 0 1');
        $overloadingEval = new OverloadingEval($board);
        $this->assertSame($expected, $overloadingEval->getResult());
    }
}


